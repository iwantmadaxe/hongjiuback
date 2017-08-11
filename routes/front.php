<?php
// 将首页路由跳转到后台管理端
Route::get('/', function () {
    return redirect('/admin');
});

session_start();
Route::get('/home/{wechat}', function ($wechat, \Illuminate\Http\Request $request) {   //wechat表示 不同的公众号 具体为代理商在 agent info 表中的id
	$id = str_replace('wechat_', '', $wechat);
	$agent = \App\Models\AgentInfo::find($id);
	$allUrl = $request->fullUrl();

	$config = [
		'app_id' => $agent->app_id,
		'secret' => $agent->app_secret,
		'oauth' => [
			'scopes'   => ['snsapi_userinfo'],
			'callback' => '/oauth_callback/'.$wechat.'?reurl='.$allUrl,
		],
	];
	$app = new \EasyWeChat\Foundation\Application($config);
	$oauth = $app->oauth;

	// 未登录
	if (empty($_SESSION['wechat_user'])) {
		return $oauth->redirect();
		// 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
		// $oauth->redirect()->send();
	}
	// 已经登录过
	$user = $_SESSION['wechat_user'];

	$oauthInfo = \App\Models\OauthCredential::where('oauth_id', $user['id'])->where('oauth_name', 'wechat')->first();
    if ($oauthInfo) {
        $findUser = \App\Models\User::where('id', $oauthInfo['user_id'])->first();
    } else {
        $findUser = null;
    }

	if ($findUser) { // 已经注册的
        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($findUser);
        \Illuminate\Support\Facades\Cookie::queue('token', $token, null, null, null, false, false);
    } else { // 代理注册
        // 创建用户
        $userInfo['name'] = $user['name'] ?: ($user['nickname']?:'用户'.time());
        $userInfo = \App\Models\User::create($userInfo);
        // 创建微信信息
        $tpl['oauth_id'] = $user['id'];
        $tpl['oauth_name'] = 'wechat';
        $tpl['user_id'] = $userInfo['id'];
        \App\Models\OauthCredential::create($tpl);
    }

	\Illuminate\Support\Facades\Cookie::queue('openid', $user['id']);
	\Illuminate\Support\Facades\Cookie::queue('wechat', $id);
    return view('home');
});

Route::get('/oauth_callback/{wechat}', function ($wechat, \Illuminate\Http\Request $request) {
	$id = str_replace('wechat_', '', $wechat);
    $allUrl = $request->input('reurl'); // 所有的url

	$agent = \App\Models\AgentInfo::find($id);
	$config = [
		'app_id' => $agent->app_id,
		'secret' => $agent->app_secret,
	];
	$app = new \EasyWeChat\Foundation\Application($config);
	$oauth = $app->oauth;
	// 获取 OAuth 授权结果用户信息
	$user = $oauth->user();
	$_SESSION['wechat_user'] = $user->toArray();
//	$targetUrl = '/home/'.$wechat;
	$targetUrl = $allUrl;
	header('location:'. $targetUrl); // 跳转到 user/profile
});