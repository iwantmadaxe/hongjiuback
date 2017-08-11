<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/8/7
 * Time: 下午6:00
 */

namespace App\API\V1\Requests;


class PointPackageApplyRequest extends BaseFormRequest
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
            'package_id' => 'required',
            'card_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'package_id.required' => '套餐号必填',
            'card_id.required' => '卡号必填',
        ];
    }
}