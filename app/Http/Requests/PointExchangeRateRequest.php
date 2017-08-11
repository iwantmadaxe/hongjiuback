<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointExchangeRateRequest extends FormRequest
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
            'points' => 'required|integer|min:1',
            'des' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'points.required' => '兑换比率必填',
            'points.integer' => '兑换比率必为整数',
            'points.min' => '兑换比率必大于0',
            'des.required' => '兑换描述必填',
        ];
    }
}
