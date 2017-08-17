<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

/**
 * 认证用户控制器
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AuthController extends BaseController
{
    use AuthenticatesUsers;
    protected $guard = 'admin';

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('name','password');
        if ( $token = Auth::guard('admin')->attempt($credentials) ) {
            return response()->json(['message'=>'登录成功','token' => $token,'name'=>$credentials['name']]);
        } else {
            return response()->json(['message'=>'登录失败','token'=>false]);
        }
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return response()->json(['message'=>'退出成功']);

    }

}
