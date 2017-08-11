<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
	use AuthenticatesUsers;

	public function username()
	{
		return 'username';
	}

	public function login(AdminLoginRequest $request)
	{
		$this->validateLogin($request);

		if ($this->attemptLogin($request)) {
			$request->session()->regenerate();
			$this->clearLoginAttempts($request);

			return $this->response()->array(['data' => ['message' => '登录成功']]);
		}

		$this->incrementLoginAttempts($request);

		return $this->response()->error('用户名或密码错误', 400, 400100);
	}

	protected function guard()
	{
		return Auth::guard('admin');
	}

	public function logout(Request $request)
	{
		$this->guard()->logout();

		$request->session()->flush();

		$request->session()->regenerate();

		return redirect('/admin');
	}
}