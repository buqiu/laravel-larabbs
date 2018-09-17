<?php
/**
 * 图片验证码类
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    /**
     * 图片验证码
     *
     * @param CaptchaRequest $request
     * @param CaptchaBuilder $captchaBuilder
     * @return mixed
     */
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-' . str_random(15);
        $phone = $request->phone;

        $captcha = $captchaBuilder->build();
        $expired_at = now()->addMinutes(2);
        \Cache::put($key, ['phone' => $phone, 'code', $captcha->getPhrase()], $expired_at);
        $result = [
            'captcha_key' => $key,
            'expired_at' => $expired_at,
            'captcha_image_content' => $captcha->inline(),
        ];
        return $this->response->array($result)->setStatusCode(201);
    }
}
