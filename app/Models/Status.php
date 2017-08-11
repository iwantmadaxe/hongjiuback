<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'telecom_status';

    protected $fillable = ['is_dead'];
}
