<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentInfo extends Model
{
    protected $table = 'agent_info';

	protected $fillable = [
		'user_id',
		'parent_id',
		'name',
		'has_wechat',
		'discount',
		'seal_discount',
		'app_id',
		'app_secret',
		'merchant',
		'key',
		'token',
		'status',
	];

    public function parentAgent()
    {
        return $this->belongsTo('App\Models\AgentInfo', 'parent_id', 'user_id');
    }

    public function adminAccount()
    {
        return $this->belongsTo('App\Models\Admin', 'user_id');
    }
}
