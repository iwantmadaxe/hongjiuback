<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthCredential extends Model
{
    protected $table = 'oauth_credential';

    protected $fillable = ['user_id', 'oauth_id', 'oauth_name', 'oauth_access_token', 'oauth_expires'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
