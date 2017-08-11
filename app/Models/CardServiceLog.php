<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardServiceLog extends Model
{
    protected $table = 'card_service_logs';

    protected $fillable = ['card_id', 'log', 'status'];
}
