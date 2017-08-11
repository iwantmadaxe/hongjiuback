<?php

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Models\Permission;
use App\Models\Role;
use App\Transformers\PermissionTransformer;
use Illuminate\Support\Facades\Auth;

class PermissionController extends BaseController
{
    /**
     * 权限列表
     * @return mixed
     */
    public function index()
    {
        $permission = Permission::all();

        return $this->response()->collection($permission, PermissionTransformer::class);
    }

    /**
     * 某角色所拥有的权限
     * @param $id
     * @return mixed
     */
    public function permissionOfRole($id)
    {
        $info = Role::where('id', $id)->first()->perms()->get();

        return $this->response()->item($info, PermissionTransformer::class);
    }

    /**
     * 获取当前用户的权限
     * @return mixed
     */
    public function myPermission()
    {
        $role = Auth::guard('admin')->user()->roles()->first();
        if (!$role) {
            return $this->response()->errorNotFound('角色不存在');
        }
        $info = Role::where('id', $role->id)->first()->perms()->get();

        return $this->response()->item($info, PermissionTransformer::class);
    }
}
