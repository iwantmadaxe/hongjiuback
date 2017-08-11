<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RolePermissionRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use App\API\V1\BaseController;
use App\Transformers\RoleListTransformer;
use App\Transformers\RoleTransformer;

class RoleController extends BaseController
{
	public function getList()
	{
		$roles = Role::all()->pluck('display_name', 'id');
		return $this->response()->array(['data' => $roles]);
	}

	public function index(RoleListTransformer $listTransformer)
	{
		$roles = Role::all();
		return $this->response()->collection($roles, $listTransformer);
	}

    /**
     * 创建角色
     * @param RoleCreateRequest $request
     * @return mixed
     */
    public function store(RoleCreateRequest $request)
    {
        $info = Role::create($request->all());
        if (!$info) {
            return $this->response()->errorBadRequest('创建失败');
        }
        return $this->response()->array(['message' => 'success']);
	}

    /**
     * 编辑角色
     * @param $id
     * @param RoleUpdateRequest $request
     * @return mixed
     */
    public function update($id, RoleUpdateRequest $request)
    {
        $info = Role::where('id', $id)->update($request->except(['id']));

        if (!$info) {
            return $this->response()->errorBadRequest('更新失败');
        }
        return $this->response()->array(['message' => 'success']);
	}

    /**
     * 角色详情
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $info = Role::where('id', $id)->first();
        if (!$info) {
            return $this->response()->errorNotFound('角色不存在');
        }
        return $this->response()->item($info, RoleTransformer::class);
	}

    /**
     * 给角色赋权限
     * @param $id
     * @param RolePermissionRequest $request
     * @return mixed
     */
    public function permission($id, RolePermissionRequest $request)
    {
        $perms = $request->input('id');
        $role = Role::find($id);
        if (!$role) {
            return $this->response()->errorNotFound('角色不存在');
        }
        $info = $role->perms()->sync($perms);

        if (!$info) {
            return $this->response()->errorBadRequest('更新失败');
        }
        return $this->response()->array(['message' => 'success']);
	}
}