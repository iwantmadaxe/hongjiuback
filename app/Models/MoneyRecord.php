<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneyRecord extends Model
{
    protected $table = 'money_record';

	protected $fillable = [
		'type',
		'money',
		'agent_id',
		'operator_id',
	];

    public function agent()
    {
        return $this->belongsTo('App\Models\Admin', 'agent_id');
	}

    public function fromAgent()
    {
        return $this->belongsTo('App\Models\Admin', 'operator_id');
	}
}
