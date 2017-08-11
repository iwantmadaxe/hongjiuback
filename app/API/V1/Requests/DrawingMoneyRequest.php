<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/31
 * Time: 上午11:18
 */

namespace App\API\V1\Requests;


class DrawingMoneyRequest extends BaseFormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'points.required' => '提现积分必填',
            'points.integer' => '体现积分必为整数',
            'points.min' => '体现积分必须大于0',
        ];
    }
}