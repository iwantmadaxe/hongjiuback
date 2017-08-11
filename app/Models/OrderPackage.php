<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPackage extends Model
{
    protected $table = 'order_package';

	protected $fillable = [
		'user_id',
		'package_id',
		'order_id',
		'package_name',
		'package_price',
        'package_type',
		'start_time',
		'end_time',
	];
}
