<?php
/**
 * 用户数据处理
 */

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * 可用包括
     *
     * @var array
     */
    protected $availableIncludes = ['role'];

    /**
     * 用户数据
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
            'introduction' => $user->introduction,
            'bound_phone' => $user->phone ? true : false,
            'bound_wechat' => ($user->weixin_unionid || $user->weixin_openid) ? true : false,
            'last_actived_at' => $user->last_actived_at->toDateTimeString(),
            'registration_id' => $user->registration_id,
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
        ];
    }

    /**
     * 导入角色
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRole(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }
}