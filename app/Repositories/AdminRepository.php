<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Repositories;

use App\Models\Admin;

class AdminRepository extends BaseRepository
{
	public function __construct(Admin $admin)
	{
		$this->model = $admin;
	}

    public function getAgentList(Admin $admin)
    {
        $agents = [];
        if ($admin->roles->first() && str_contains($admin->roles->first()->name, 'manager')) {
            $this->model->with('agent')->has('agent', '>', 0)->get()->each(function ($v) use (&$agents) {
                $agents[$v['agent']['user_id']] = $v['agent']['name'];
            });
        } else {
            $ownInfo = $this->model->with('agent')->where('id', $admin->id)->has('agent', '>', 0)->first();
            if ($ownInfo) {
                $agents[$ownInfo['agent']['user_id']] = $ownInfo['agent']['name'];
                $next= $ownInfo['agent']['parentAgent'];
                while ($next) {
                    $agents[$next['user_id']] = $next['name'];
                    $next = $next['parentAgent'];
                }
            }
        }

        return $agents;
	}
}