<?php
/**
 * 短信验证码类
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\VerificationCodeRequest;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodesController extends Controller
{

    /**
     * 发送验证码
     *
     * @param VerificationCodeRequest $request
     * @param EasySms $easySms
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captcha_data = \Cache::get($request->captcha_key);

        if (!$captcha_data) {
            return $this->response->error('图片验证码已失效', 422);
        }
        if (!hash_equals($captcha_data['code'], $request->captcha_code)) {
            // 验证码错误就清除缓存
            \Cache::forget($request->captcha_key);
            return $this->response->errorUnauthorized('验证码错误');
        }

        $phone = $captcha_data['phone'];

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成 4 位随机数，左侧补 0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $result = $easySms->send($phone, [
                    'content' => "【解螺旋官网】您的验证码是{$code}。如非本人操作，请忽略本短信",
                ]);
            } catch (NoGatewayAvailableException $exception) {
                $message = $exception->getException('yunpian')->getMessage();
                return $this->response->errorInternal($message ?? '短信发送异常');
            }
        }
        $key = 'verificationCode_' . str_random(15);
        $expired_at = now()->addMinutes(10);

        // 缓存验证码 10 分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expired_at);

        // 清除图片验证码缓存

        \Cache::forget($request->captcha_key);
        return $this->response->array([
            'key' => $key,
            'expired_at' => $expired_at->toDateTimeString(),
        ])->setStatusCode(201);
    }

}