<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsRecord extends Model
{
    protected $table = 'sms_record';

	protected $fillable = [
		'code',
		'phone',
		'type',
		'expiration'
	];
}
