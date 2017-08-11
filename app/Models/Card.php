<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
	use SoftDeletes;

	const STATUS = [
		'disabled' => 3,
	];

	protected $fillable = [
		'iccid', 'acc_number', 'code', 'telecom_id', 'tel', 'agent_id', 'other_name',
        'type', 'status', 'created_time', 'is_forbidden', 'user_id', 'overdue_bill'
	];

    public function telecomAccount()
	{
		return $this->belongsTo('App\Models\TelecomAccount', 'telecom_id');
	}

	public function package()
	{
		return $this->hasOne('App\Models\CardPackage', 'card_id');
	}

	public function agent()
	{
		return $this->hasOne('App\Models\Admin', 'id', 'agent_id');
	}

	public function flow()
	{
		return $this->hasOne('App\Models\Flow', 'card_id', 'id');
	}

    public function certification()
    {
        return $this->hasMany('App\Models\Certificate', 'card_id');
	}
}
