<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    protected $table = 'money';

	protected $fillable = [
		'agent_id',
		'balance',
		'all_money',
	];

	public function agent()
	{
		return $this->hasOne('App\Models\AgentInfo', 'user_id', 'agent_id');
	}

	public function admin()
	{
		return $this->hasOne('App\Models\Admin', 'id', 'agent_id');
	}
}
