<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Controllers\Auth;

use App\API\V1\Requests\UserLoginRequest;
use App\Exceptions\HttpException;
use App\API\V1\Controllers\Auth\Credentials\AbstractCredential;

class LoginManager
{
	private $validAuthName = [
		'phone',
		'sms',
		//'local'
	];

	public function __construct(UserLoginRequest $request)
	{
		$this->credential = $request->all();
		if (! $this->checkAuthName($this->credential['auth_name'])) {
			throw new HttpException('无效的登录方式');
		}
		$namespace = '\\App\\API\\V1\\Controllers\\Auth\\Credentials\\';
		$className = $namespace.ucfirst($this->credential['auth_name']).'Credential';

		if (new $className() instanceof AbstractCredential) {
			$this->credentialManager = new $className();
		} else {
			throw new HttpException('无效的登录方式');
		}
	}

	public function userExist()
	{
		return $this->credentialManager->checkUserExist($this->credential);
	}

	public function login()
	{
		return $this->credentialManager->login($this->credential);
	}

	public function isForbidden($userId)
	{
		return $this->credentialManager->isForbidden($userId);
	}

	private function checkAuthName($authName)
	{
		return in_array($authName, $this->validAuthName) ? true : false;
	}
}