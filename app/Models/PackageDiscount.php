<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageDiscount extends Model
{
	use SoftDeletes;

    protected $table = 'package_discount';

	protected $fillable = [
		'agent_id',
		'package_id',
		'discount',
	];

	public function package()
	{
		return $this->hasOne('App\Models\Package', 'id', 'package_id');
	}

	public function agent()
	{
		return $this->hasOne('App\Models\Admin', 'id', 'agent_id');
	}
}
