<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Exceptions\UserException;
use App\Http\Requests\UserIncrementRequest;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use App\Transformers\AdminMessageTransformer;
use App\Transformers\UserListTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends BaseController
{
	private $userRepository;
	private $adminRepository;

	public function __construct(AdminRepository $adminRepository, UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
		$this->adminRepository = $adminRepository;
	}

	public function notify(AdminMessageTransformer $messageTransformer)
	{
		$user = Auth::guard('admin')->user();
		$notify = $user->unreadNotifications;
		return $this->response()->collection($notify, $messageTransformer);
	}

	public function getUsers(UserListTransformer $listTransformer, Request $request)
	{
	    // 判断是否是管理员
        $admin = Auth::guard('admin')->user();
        if ($admin->isAdmin()) {
            $users = $this->userRepository->with('recommendation')->get($request)->paginate();
        } else {
            $users = $this->userRepository->with('recommendation')->setAgent($admin->id)->get($request)->paginate();
        }
		return $this->response()->paginator($users, $listTransformer);
	}

    /**
     * 禁用、解除禁用用户。
     *
     * @param $userId
     * @param $enable
     * @return mixed
     */
	public function enable($userId, $enable)
	{
		$this->userRepository->checkExist($userId);
		if ($this->userRepository->enable($userId, $enable)) {
			return $this->response()->array(['data' => ['message' => '操作成功']]);
		}
	}

	public function delete($userId)
	{
		$this->userRepository->checkExist($userId);
		if ($this->userRepository->delete($userId)) {
			return $this->response()->array(['data' => ['message' => '操作成功']]);
		}
	}

	public function state()
	{
		//先看看缓存里面有没有啊
		if ( !Cache::has('user_state')) {
			//缓存一波
			Cache::remember('user_state', 60, function () {
				 $state = [
					'day'   => $this->userRepository->newUserSince(Carbon::today()->toDateTimeString()),
					'week'  => $this->userRepository->newUserSince(Carbon::now()->startOfWeek()->toDateTimeString()),
					'month' => $this->userRepository->newUserSince(Carbon::now()->startOfMonth()->toDateTimeString()),
				];
				$state['count'] = $this->userRepository->all()->count();
				return $state;
			});
		}
		$state = Cache::get('user_state');
		return $this->response()->array(['data' => $state]);
	}

	public function increment(UserIncrementRequest $request)
	{
	    $admin = Auth::guard('admin')->user();
        if ($admin->isAdmin()) {
            $increnment = $this->userRepository
                ->increment($request['start_date'], $request['end_date']);
        } else {
            $increnment = $this->userRepository->setAgent($admin->id)
                ->increment($request['start_date'], $request['end_date']);
        }
		return $this->response()->array(['data' => $increnment]);
	}
}