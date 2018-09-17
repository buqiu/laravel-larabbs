<?php
/**
 * API 接口基类
 */

namespace App\Http\Controllers\Api\V1;

use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    use Helpers;

    /**
     * 返回错误提示
     *
     * @param $status_code
     * @param null $message
     * @param int $code
     */
    public function errorResponse($status_code, $message = null, $code = 0)
    {
        throw new HttpException($status_code, $message, null, [], $code);
    }
}
