<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
	use Notifiable;
	use SoftDeletes;

    protected $fillable = ['name', 'phone', 'email', 'address', 'area_code', 'is_forbidden'];

	public function cards()
	{
		return $this->hasMany('App\Models\Card', 'user_id', 'id');
	}

    public function recommendation()
    {
        return $this->hasOne('App\Models\Recommend', 'user_id');
	}
}
