<?php

namespace App\Http\Requests;

use App\API\V1\Requests\BaseFormRequest;

class UserUpdatePhoneRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|'.$this->phone,
			'smsCode' => 'required',
        ];
    }
}
