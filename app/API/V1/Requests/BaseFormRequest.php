<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseFormRequest extends FormRequest
{

	protected $phone = 'regex:/^1[34578][0-9]{9}$/';

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}
}