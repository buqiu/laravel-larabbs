<?php
/**
 * 权限数据处理
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Permission;

class PermissionTransformer extends TransformerAbstract
{
    /**
     * 权限数据
     *
     * @param Permission $permission
     * @return array
     */
    public function transform(Permission $permission)
    {
        return [
            'id' => $permission->id,
            'name' => $permission->name,
        ];
    }

}