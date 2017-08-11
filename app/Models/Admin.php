<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Admin extends Authenticatable
{
	use Notifiable;
	use SoftDeletes { restore as private restoreB; }
    use EntrustUserTrait { restore as private restoreA; }

    protected $fillable = ['username', 'password', 'status', 'name', 'phone', 'email', 'address', 'openid', 'card_id', 'area_code'];

	public function agent()
	{
		return $this->hasOne('App\Models\AgentInfo', 'user_id', 'id');
	}

    public function restore()
    {
        $this->restoreA();
        $this->restoreB();
    }

    public function isAdmin()
    {
        return str_contains($this->roles()->first()->name, 'manager');
    }
}
