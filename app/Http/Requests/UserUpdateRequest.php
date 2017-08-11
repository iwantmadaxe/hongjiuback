<?php

namespace App\Http\Requests;

use App\API\V1\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends BaseFormRequest
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
//			'phone' => 'required|'.$this->phone,
//			'cardNum' => 'required|numeric',
			//'password' => 'required',
			'name' => 'required',
			'email' => 'required|email',
			//'smsCode' => 'required',
			'areaCode' => 'required',
			'address' => 'required',
			//'openid' => 'required',
			//'wechat' => 'required',    //来自哪个公众号
        ];
    }
}
