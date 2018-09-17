<?php
/**
 * 用户类
 */

namespace App\Http\Controllers\Api\V1;

use App\Models\Image;
use Auth;
use App\Http\Requests\Api\V1\UserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 注册用户信息
     *
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $verify_data = \Cache::get($request->verification_key);

        if (!$verify_data) {
            return $this->response->error('验证码以失效', 422);
        }

        if (!hash_equals($verify_data['code'], $request->verification_code)) {
            // 返回 401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->response->item($this->user, new UserTransformer())
            ->setMeta([
                'access_token' => Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);;
    }

    /**
     * 获取用户信息
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }

    /**
     * 编辑用户信息
     *
     * @param UserRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction', 'registration_id']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * 活跃用户
     *
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function activedIndex(User $user)
    {
        return $this->response->collection($user->getActiveUsers(), new  UserTransformer());
    }

}
