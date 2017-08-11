<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/28
 * Time: 下午5:45
 */

namespace App\API\V1\Requests;


class DrawMoneyRequest extends BaseFormRequest
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
            'points' => 'required|integer|min:0',
            'card_no' => 'required',
            'card_bank' => 'required',
            'card_owner' => 'required',
            'card_owner_phone' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'points.required' => '提现积分必填',
            'points.integer' => '体现积分必为整数',
            'points.min' => '体现积分必须大于0',
            'card_no.required' => '银行卡号必填',
            'card_bank.required' => '所属银行必填',
            'card_owner.required' => '持卡人必填',
            'card_owner_phone.required' => '联系电话必填',
        ];
    }
}