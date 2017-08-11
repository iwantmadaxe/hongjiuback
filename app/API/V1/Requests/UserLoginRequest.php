<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Requests;

class UserLoginRequest extends BaseFormRequest
{
	public function rules()
	{
		return [
			'phone' => $this->phone,
			'auth_name' => 'required',
		];
	}
}