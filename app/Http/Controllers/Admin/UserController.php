<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Admin\AdminAddRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\Admin;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends AdminController
{

    /**
     * @var UserService
     */
    public  $userService;

	public function __construct(UserService $userService)
	{
	    $this->userService = $userService;
	}

    /**
     * 创建管理员
     * @param AdminAddRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function addAdmin(AdminAddRequest $request)
    {
        $credentials = $request->all();
        $credentials['password'] = bcrypt($request->input('password'));

        $result = $this->userService->addAdmin($credentials);
        if($result instanceof Admin){
            return $this->success(['message'=>'创建成功']);
        }else{
            return $this->error(400000,400,'创建失败');
        }
    }

    /**
     * 更新管理员
     * @param AdminUpdateRequest $request
     * @return JsonResponse
     */
    public function updateAdmin(AdminUpdateRequest $request)
    {
        $credentials = $request->all();
        if(isset($credentials['password'])){
            $credentials['password'] = bcrypt($request->input('password'));
        }
        $result = $this->userService->updateAdmin($credentials);
        if($result){
            return $this->success(['message'=>'更新成功']);
        }else{
            return $this->error(400000,400,'更新失败');
        }
    }

    /**
     * 获取管理员列表
     * @param $page
     * @return JsonResponse
     */
    public function adminList($page)
    {
        $result = $this->userService->adminList($page);
       return  $this->responsePage($result);
    }


}