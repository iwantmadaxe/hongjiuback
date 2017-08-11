<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use Illuminate\Support\Facades\Config;

class Role extends EntrustRole
{
    protected $fillable = ['name', 'display_name', 'description'];

    public function users()
    {
        return $this->belongsToMany(Config::get('auth.providers.admins.model'), Config::get('entrust.role_user_table'), Config::get('entrust.role_foreign_key'), Config::get('entrust.user_foreign_key'));
    }
}
