<?php

namespace App\API\V1\Requests;


class AddressCreateRequest extends BaseFormRequest
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
            'receiver' => 'required',
            'contact' => 'required',
            'area' => 'required',
            'address' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'receiver.required' => '收货人必填',
            'contact.required' => '联系方式必填',
            'area.required' => '收货区域必选',
            'address.required' => '收货详细地址必填'
        ];
    }
}
