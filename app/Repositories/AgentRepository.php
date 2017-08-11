<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Repositories;

use App\Models\Admin;
use App\Models\AgentInfo;
use App\Models\RoleUser;

class AgentRepository extends BaseRepository
{
	public function __construct(Admin $admin)
	{
		$this->model = $admin;
	}

	public function getAgents()
	{
		return $this->model->join('role_user', 'admins.id', '=', 'role_user.user_id')->where('role_user.role_id', 1)->paginate();
	}

	public function get($id)
	{
		return Admin::find($id);
	}

    public function parentsNumBy($id)
    {
        $num = 0;
        $adminInfo = Admin::with('agent.parentAgent.parentAgent')
            ->where('id', $id)->first();
        logger($adminInfo);

        if ($adminInfo && $adminInfo->agent) {
            $next = $adminInfo['agent'];
            while ($next) {
                $next = isset($next['parentAgent']) ? $next['parentAgent'] : null;
                $num++;
            }
        }

        return $num;
	}

	public function updateAgent($agent, $id)
	{
		$admin = [
			'username' => $agent['username'],
		//	'password' => bcrypt($agent['password']),
		];
		$this->beginTransaction();
		try {
		$admin = $this->model->find($id)->update($admin);
		$agent_info = [
			'name' => $agent['name'],
			'has_wechat' => $agent['has_wechat'],
			'discount' => $agent['discount'],
			'seal_discount' => $agent['seal_discount'],
		];
		if ($agent['has_wechat']) {
			$agent_info = array_merge($agent_info, [
				'app_id' => $agent['app_id'],
				'app_secret' => $agent['app_secret'],
				'merchant' => $agent['merchant'],
				'key' => $agent['key'],
				'token' => $agent['token'],
			]);
		}
		AgentInfo::updateOrCreate(['user_id' => $id], $agent_info);
		$this->commit();
		return true;
		} catch (\Exception $e) {
		$this->rollback();
		return false;
		}
	}

	public function createAgent($agent)
	{
		$admin = [
			'username' => $agent['username'],
			'password' => bcrypt($agent['password']),
            'status' => 1
		];
		$this->beginTransaction();
		try {
			$admin_id = $this->model->create($admin)->id;
			$agent_info = [
				'user_id' => $admin_id,
				'parent_id' => $agent['parent_id'] ?: null,
				'name' => $agent['name'],
				'has_wechat' => $agent['has_wechat'],
				'discount' => $agent['discount'],
				'seal_discount' => $agent['seal_discount'],
			];
			if ($agent['has_wechat']) {
				$agent_info = array_merge($agent_info, [
					'app_id' => $agent['app_id'],
					'app_secret' => $agent['app_secret'],
					'merchant' => $agent['merchant'],
					'key' => $agent['key'],
					'token' => $agent['token'],
				]);
			}
			AgentInfo::create($agent_info);
			RoleUser::create(['user_id' => $admin_id, 'role_id' => 1]);
			$this->commit();
			return true;
		} catch (\Exception $e) {
			$this->rollback();
			return false;
		}
	}
}