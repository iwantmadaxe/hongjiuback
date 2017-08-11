<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointRecord extends Model
{
    // type:1是购卡奖励，2是充值奖励，3是提现扣除，4是兑换扣除
    protected $table = 'point_records';

    protected $fillable = ['sponsor', 'receiver', 'point', 'type', 'pointable_id', 'pointable_type'];

    public function receiverUser()
    {
        return $this->belongsTo('App\Models\User', 'receiver');
    }

    public function sponsorUser()
    {
        return $this->belongsTo('App\Models\User', 'sponsor');
    }

    public function pointable()
    {
        return $this->morphTo();
    }
}
