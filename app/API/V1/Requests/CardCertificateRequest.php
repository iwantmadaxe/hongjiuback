<?php

namespace App\API\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardCertificateRequest extends FormRequest
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
            'card_code' => 'required',
			'id_number' => 'required',
			'front_image' => 'required',
			'back_image' => 'required',
            'phone' => 'required',
            'name' => 'required',
            'smsCode.required' => '手机验证码必填'
        ];
    }

    public function messages()
    {
        return [
            'card_code.required' => '卡号必填',
            'id_number.required' => '身份证号必填',
            'front_image.required' => '身份证必传',
            'back_image.required' => '身份证必传',
            'phone.required' => '手机号必填',
            'name.required' => '姓名必填',
            'smsCode.required' => '手机验证码必填'
        ];
    }
}
