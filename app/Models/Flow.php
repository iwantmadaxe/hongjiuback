<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    protected $table = 'flow';

	protected $fillable = [
		'card_id',
		'flow',
		'total_flow',
		'used',
		'speed',
		'level',
		'last_time',
        'last_flow',
        'last_flow_time'
	];

	protected $dates = ['last_flow_time'];
}
