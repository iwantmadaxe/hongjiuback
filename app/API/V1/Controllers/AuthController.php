<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Api\V1\Controllers;

use App\API\V1\Requests\UserResetPasswordRequest;
use App\Models\LocalCredential;
use App\Models\User;
use App\Repositories\CardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\API\V1\BaseController;
use App\API\V1\Controllers\Auth\LoginManager;
use App\API\V1\Requests\UserRegisterRequest;
use App\Repositories\UserRepository;
use App\Services\SmsService;

class AuthController extends BaseController
{
	private $smsService;
	private $userRepository;

	public function __construct(UserRepository $userRepository, SmsService $smsService)
	{
		$this->smsService = $smsService;
		$this->userRepository = $userRepository;
	}

	public function register(UserRegisterRequest $request)
	{
		$this->smsService->check($request['phone'], $request['smsCode'], 'register');    //验证码检查
		//todo 这张卡是否属于这个代理商(暂时不限制)
		//todo 检查 card, openid是否已经注册
//        $openid = Cookie::get('openid');
//        $isUser = User::where('openid', $openid)->count();
//        if (!$isUser) {
//            $userInfo['openid'] = $openid;
//        }

		$userInfo = $request->only(['name', 'phone', 'email', 'address']);
		$userInfo['area_code'] = $request['areaCode'];
		$userInfo['password'] = bcrypt($request['password']);

		if ($user = $this->userRepository->register($userInfo)) {
			return $this->response()->array(['data' => ['message' => '用户注册成功']]);
		} else {
			return $this->response()->error('用户注册失败', 400, 400200);
		}
	}

	public function login(LoginManager $loginManager)
	{
		$loginManager->userExist();    //检查账号是否已经注册
		if ($user = $loginManager->login()) {
			$data = [
				'token' => JWTAuth::fromUser($user)
			];
            Cookie::queue('token', $data['token'], null, null, null, false, false);
			return $this->response()->array(compact('data'));
		} else {
			return $this->response()->error('用户名或密码错误', 400, 400201);
		}
	}

	public function resetPassword(UserResetPasswordRequest $request)
	{
		if (! User::where('phone', $request['phone'])->first()) {
			return $this->response()->errorNotFound('用户不存在', 404001);
		}
		$this->smsService->check($request['phone'], $request['smsCode'], 'password');	  //检查code是否有效

		$user = LocalCredential::where('phone', $request['phone'])->first();
		$user->password = bcrypt($request['password']);

		if ($user->save()) {
			return $this->response()->array(['data' => ['message' => '密码已重置']]);
		} else {
			return $this->response()->error('密码已重置失败', 400, 400202);
		}
	}


	public function logout(Request $request)
	{
		try {
			if ( !$token = JWTAuth::getToken()) {
				return $this->response()->array(['data' => ['message' => '您已退出']]);
			} else {
			    $user = $request['auth_user'];
				if (JWTAuth::invalidate($token)) {
				    Cookie::queue('openid', null);
				    Cookie::queue('wechat', null);
				    Cookie::queue('token', null);
					return $this->response()->array(['data' => ['message' => '退出登录成功']]);
				} else {
					throw new \HttpException('退出登录失败');
				}
			}
		} catch (TokenBlacklistedException $e) {
			return $this->response()->array(['data' => ['message' => '您已退出']]);
		}
	}
}