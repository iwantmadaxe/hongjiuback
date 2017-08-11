<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Requests;

class UserRegisterRequest extends BaseFormRequest
{
	public function rules()
	{
		return [
			'name' => 'required',
			'phone' => 'required|unique:users|'.$this->phone,
			'password' => 'required',
			//'email' => 'required|email',
			'smsCode' => 'required',
			'areaCode' => 'required',
			'address' => 'required',
			'openid' => 'required',
			'wechat' => 'required',    //来自哪个公众号
		];
	}
}