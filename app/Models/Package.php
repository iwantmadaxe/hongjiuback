<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'flow_value',
		'flow',
		'name',
		'display_name',
		'price',
		'is_back',
		'is_apart',
		'type',
        'instruction',
		'is_back',
		'status',
		'order',
        'is_exchange',
        'points'
	];

    public function discount()
    {
        return $this->hasMany('App\Models\PackageDiscount', 'package_id');
	}
}
