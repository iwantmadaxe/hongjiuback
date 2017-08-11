<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $table = 'telecom_account';

	protected $fillable = [
		'name',
		'account',
		'password',
		'key1',
		'key2',
		'key3',
		'note',
	];
}
