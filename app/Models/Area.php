<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';

    public function area()
    {
        return $this->belongsTo('App\Models\Area', 'belongs', 'code');
    }
}
