<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Controllers\Auth\Credentials;

use App\Exceptions\UserException;
use App\Models\User;
use App\Services\SmsService;
use App\Models\LocalCredential as LocalCredentialModel;
use Illuminate\Support\Facades\Cookie;

class SmsCredential extends AbstractCredential
{
	public function checkUserExist(array $credential)
	{
		if (! LocalCredentialModel::where('phone', $credential['phone'])->first()) {
			throw new UserException('手机号码未注册', 500301);
		}
	}

	public function login(array $credential)
	{
		if((new SmsService())->check($credential['phone'], $credential['smsCode'], 'login')) {
			$user = LocalCredentialModel::where('phone', $credential['phone'])->first();
			return $user->user;
		} else {
			return false;
		}
	}
}