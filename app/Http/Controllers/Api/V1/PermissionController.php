<?php
/**
 * 权限类
 */

namespace App\Http\Controllers\Api\V1;

use App\Transformers\PermissionTransformer;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * 当前登录用户权限
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $permissions = $this->user()->getAllPermissions();

        return $this->response->collection($permissions, new PermissionTransformer());
    }
}
