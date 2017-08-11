<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/25
 * Time: 下午5:27
 */

namespace App\API\V1\Requests;


class CardBuyOrderRequest extends BaseFormRequest
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
            'address' => 'required',
            'amount' => 'required|integer|min:0|max:5'
        ];
    }

    public function messages()
    {
        return [
            'address.required' => '收货地址必选',
            'amount.required' => '卡数量必填',
            'amount.integer' => '卡数量必填',
            'amount.min' => '卡数量必填',
            'amount.max' => '超过购卡上限'
        ];
    }
}