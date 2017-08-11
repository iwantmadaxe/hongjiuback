<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointPackageRecord extends Model
{
    // status 1:在处理; 2:提交运营商成功, 3:处理失败
    protected $table = 'point_package_records';

    protected $fillable = ['user_id', 'package_id', 'card_id', 'consume_points', 'status'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->withTrashed();
    }

    public function package()
    {
        return $this->belongsTo('App\Models\Package', 'package_id')->withTrashed();
    }

    public function card()
    {
        return $this->belongsTo('App\Models\Card', 'card_id');
    }
}
