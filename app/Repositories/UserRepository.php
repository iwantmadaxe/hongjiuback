<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Repositories;

use App\Exceptions\UserException;
use App\Models\LocalCredential;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserRepository extends BaseRepository
{
	private $localCredential;

	private $agent;

	public function __construct(User $user, LocalCredential $localCredential)
	{
		$this->model = $user;
		$this->localCredential = $localCredential;
	}

    public function setAgent($agent = null)
    {
        $this->agent = $agent;
        return $this;
	}

	public function get($request)
	{
		$users = $this->model;
		if ($this->agent) {
		    $users = $users->whereHas('cards.agent', function ($query) {
		        $query->where('id', $this->agent);
            });
        }
		if ($request->has('status')) {  //0 正常  1禁用
			$users = $users->where('is_forbidden', $request['status']);
		}
		if ($request->has('keyword')) {
			$users = $users->where('name', 'like' ,$request['keyword']);
		}
		return $users;
	}

	public function checkExist($userId)
	{
		if (!$this->find($userId)) {
			throw new UserException('用户不存在', 404040);
		}
	}

	public function all()
	{
		return $this->model->all();
	}

	public function paginate()
	{
		return $this->model->paginate();
	}

	public function enable($userId, $enable = true)
	{
		$isForbidden = ($enable == 0) ? true : false;
		$user = $this->model->find($userId)->first();
		return $user->update(['is_forbidden' => $isForbidden]);
	}

	/**
	 * 从$date到此时的新增用户数
	 *
	 * @param $date
	 */
	public function newUserSince($date)
	{
		return $this->model->whereBetween('created_at', [$date, Carbon::now()->toDateTimeString()])->count();
	}

	public function increment($startDate, $endDate)
	{
	    $users = $this->model->query();
        if ($this->agent) {
            $users = $users->whereHas('cards.agent', function ($query) {
                $query->where('id', $this->agent);
            });
        }
	    return $users
            ->selectRaw('DATE_FORMAT(created_at, \'%Y-%m-%d\') days, count(id) count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('days')
            ->get();
	}

	public function delete($userId)
	{
		return $this->find($userId)->delete();
	}







	public function updateInfo(array $userInfo, $userId)
	{
		$user = $this->model->find($userId);

		return $user->update($userInfo);
	}

	public function updatePassword($password, $userId)
	{
		$localCredential = LocalCredential::where('user_id', $userId);

		return $localCredential->update(['password' => bcrypt($password)]);

	}



	public function updatePhone($phone, $userId)
	{
		$user = $this->model->find($userId);

		$user->update(['phone' => $phone]);    //用户表更新

		$this->beginTransaction();
		try {
			$localCredential = LocalCredential::where('user_id', $userId);
			$localCredential->update(['phone' => $phone]);     //local credential表更新手机号码
			$this->commit();
			return true;
		} catch (\Exception $e) {
			$this->rollback();
			return false;
		}
	}


	public function register(array $userInfo)
	{
		$this->beginTransaction();
		//try {
			$userData = collect($userInfo)->only(['name', 'phone', 'email', 'address', 'area_code', 'openid', 'card_id']);
			$user = $this->model->create($userData->toArray());

			$userLocalCredential = [
				'password' => $userInfo['password'],
				'phone' => $userInfo['phone'],
				'user_id' => $user->id,
			];
			$this->localCredential->create($userLocalCredential);
			$this->commit();

			return $user;

	//	} catch (\Exception $e) {
			$this->rollback();
			Log::info('用户注册失败, 注册信息:' . json_encode($userInfo));
			return false;
	//	}
	}

    public function __call($method, $arguments)
    {
        call_user_func_array([$this->model, $method], $arguments);
        return $this;
	}
}