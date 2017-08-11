<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointMoneyRecord extends Model
{
    // status: 1待处理，2已处理，3已拒绝
    const STATUS = [
        1 => '待处理',
        2 => '已处理',
        3 => '已拒绝',
    ];

    protected $table = 'point_money_records';

    protected $fillable = ['user_id', 'consume_points', 'exchange_money', 'status',
        'card_no', 'card_bank', 'card_owner', 'card_owner_phone'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function records()
    {
        return $this->morphMany('App\Models\PointRecord', 'pointable');
    }
}
