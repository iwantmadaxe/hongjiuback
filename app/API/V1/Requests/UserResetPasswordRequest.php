<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Requests;

class UserResetPasswordRequest extends BaseFormRequest
{
	public function rules()
	{
		return [
			'phone' => $this->phone,
			'smsCode' => 'required',
			'password' => 'required',
		];
	}
}