<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class LocalCredential extends Authenticatable
{
    protected $table = 'local_credential';

	protected $fillable = [
		'user_id',
		'phone',
		'password'
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}
