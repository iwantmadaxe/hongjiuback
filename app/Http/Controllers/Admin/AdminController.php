<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Http\Requests\AdminCreateRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\Admin;
use App\Models\RoleUser;
use App\Transformers\AdminListTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AdminController extends BaseController
{


	public function index(AdminListTransformer $listTransformer)
	{
		$admins = Admin::paginate();
		return $this->response()->paginator($admins, $listTransformer);
	}

	public function show($id, AdminListTransformer $listTransformer)
	{
		$admin = Admin::find($id);
		return $this->response()->item($admin, $listTransformer);
	}

	public function delete(Request $request)
	{
		foreach ($request['ids'] as $key => $id) {
			$admin = Admin::find($id)->delete();
		}
		return $this->response()->array(['data' => ['message' => '删除成功']]);
	}

	public function enable($enable, Request $request)
	{
		foreach ($request['ids'] as $key => $id) {
			$admin = Admin::find($id)->update(['status' => $enable]);    //0表示禁用  1表示启用
		}
		return $this->response()->array(['data' => ['message' => '修改成功']]);
	}

	public function create(AdminCreateRequest $request)
	{
		$admin = [
			'username' => $request['username'],
			'password' => bcrypt($request['password']),
			'status' => 1,
		];

		DB::beginTransaction();
		try{
            $adminRecord = Admin::create($admin);
            $adminRecord->attachRole(['id' => $request['role_id']]);
            DB::commit();
        }catch (\Exception $e) {
		    DB::rollBack();
		    return $this->response()->errorBadRequest('保存失败！');
        }

		return $this->response()->array(['data' => ['message' => '创建成功']]);
	}

	public function update(AdminUpdateRequest $request, $id)
	{
		$admin = Admin::find($id);
		if (!$admin) {
            return $this->response()->errorBadRequest('该用户已被删除！');
        }

        DB::beginTransaction();
		try {
            $params = [];
            $params['username'] = $request['username'];
            if ($request['password']) {
                $params['password'] = bcrypt($request['password']);
            }
            $adminRecord = $admin->update($params);

            $admin->roles()->sync([$request['role_id']]);
            DB::commit();
        } catch (\Exception $e) {
		    DB::rollBack();
		    logger($e);
            return $this->response()->errorBadRequest('保存失败！');
        }

		return $this->response()->array(['data' => ['message' => '修改成功']]);
	}
}