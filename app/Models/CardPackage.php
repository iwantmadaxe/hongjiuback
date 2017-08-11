<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardPackage extends Model
{
    protected $table = 'card_package';

    protected $fillable = ['card_id', 'package_id', 'expiration'];

    protected $dates = ['expiration'];

    public function package()
    {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }
}
