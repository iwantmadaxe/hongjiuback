<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table = 'certification';

	protected $fillable = [
		'status',
		'reason',
		'front_image',
		'back_image',
		'user_id',
		'code',
		'card_id',
		'id_number',
		'username',
		'phone',
	];

	const reason = [
		1 => '身份证和卡不同框',
		2 => '图片不清晰',
		3 => '图片意思PS处理',
		4 => '所填信息与证件不符',
		5 => '无效图片',
	];

	public function card()
	{
		return $this->hasOne('App\Models\Card', 'id', 'card_id');
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}
}
