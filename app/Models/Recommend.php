<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommend extends Model
{
    protected $table = 'recommends';

    protected $fillable = ['user_id', 'uuid', 'url', 'father_id', 'points'];

    /**
     * 推荐人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recommender()
    {
        return $this->belongsTo('App\Models\User', 'father_id');
    }

    /**
     * 拥有者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
