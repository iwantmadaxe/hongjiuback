<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Controllers\Auth\Credentials;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UserException;
use App\Models\LocalCredential as LocalCredentialModel;
use Illuminate\Support\Facades\Cookie;

class PhoneCredential extends AbstractCredential
{
	public function checkUserExist(array $credential)
	{
		if (! LocalCredentialModel::where('phone', $credential['phone'])->first()) {
			throw new UserException('手机号码未注册', 500301);
		}
	}

	public function login(array $credential)
	{
		$credential = collect($credential)->only(['phone', 'password'])->toArray();
		if (Auth::guard('local')->attempt($credential)) {
			$user = LocalCredentialModel::where('phone', $credential['phone'])->first();
			return $user->user;
		} else {
			return false;
		}
	}
}