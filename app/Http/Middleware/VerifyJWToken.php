<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Middleware;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class VerifyJWToken extends BaseMiddleware
{
	public function handle($request, \Closure $next)
	{
		$response = app('output.response');

		if (! $token = $this->auth->setRequest($request)->getToken()) {
			return $response->error('无效的token', 401, 401000);
		}
		try {
			$user = $this->auth->authenticate($token);
		} catch (TokenExpiredException $e) {
			return $response->error('token已过期', 401, 401001);
		} catch (JWTException $e) {
			return $response->error('无效的token', 401, 401000);
		}

		if (! $user) {
			return $response->error('用户不存在', 404, 404001);
		}

		$request['auth_user'] = $user;    //将认证用户取出 附加到request中
		return $next($request);
	}
}