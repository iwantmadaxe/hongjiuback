<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRunPackageApiRequest extends FormRequest
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
            'orders' => 'required|array',
            'type' => 'required|in:0,1'
        ];
    }

    public function messages()
    {
        return [
            'orders.required' => '订单号必选',
            'orders.array' => '订单号格式错误',
            'type.required' => '修改的检测类型必填',
            'type.in' => '检测类型范围错误'
        ];
    }
}
