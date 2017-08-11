<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/31
 * Time: 上午10:50
 */

namespace App\API\V1\Controllers\Auth\Credentials;


use App\Exceptions\UserException;
use App\Models\LocalCredential as LocalCredentialModel;
use App\Models\OauthCredential as OauthCredentialModel;
use Illuminate\Support\Facades\Auth;

class OauthCredential extends AbstractCredential
{

    public function checkUserExist(array $credential)
    {
        if (! OauthCredentialModel::where('oauth_id', $credential['openid'])->first()) {
            throw new UserException('该微信号尚未注册', 500301);
        }
    }

    public function login(array $credential)
    {
        $credential = collect($credential)->only(['openid'])->toArray();
        $oauthInfo = OauthCredentialModel::where('oauth_id', $credential['openid'])
            ->where('oauth_name', 'wechat')->first()->user()->first();
        if ($oauthInfo) {
            return $oauthInfo;
        } else {
            return false;
        }
    }
}