<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/28
 * Time: 下午3:11
 */

namespace App\API\V1\Requests;


class CardNickNameRequest extends BaseFormRequest
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
            'nick_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nick_name.required' => '卡昵称必填',
        ];
    }
}