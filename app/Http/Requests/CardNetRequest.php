<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class CardNetRequest extends FormRequest
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
            'cards' => 'required',
            'type' => 'required|in:1,2',
            'quota' => 'required|integer|specialQuota'
        ];
    }

    public function messages()
    {
        return [
            'cards.required' => '卡号必填',
            'type.required' => '限制类型必填',
            'type.in' => '限制类型范围错误',
            'quota.required' => '流量范围必填',
            'quota.integer' => '流量范围必须为整数',
            'quota.special_quota' => '流量范围要求错误',
        ];
    }

    public function validate()
    {
        Validator::extend('specialQuota', function ($attribute, $value, $parameters, $validator) {
            return ($value == -1 || $value == 0 || $value > 0);
        });

        parent::validate();
    }
}
