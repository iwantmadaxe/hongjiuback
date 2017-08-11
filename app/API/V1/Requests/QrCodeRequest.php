<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/26
 * Time: 上午12:15
 */

namespace App\API\V1\Requests;


class QrCodeRequest extends BaseFormRequest
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
            'qrurl' => 'required|url',
        ];
    }

    public function messages()
    {
        return [
            'qrurl.required' => '网址必填',
            'qrurl.url' => '网址必填',
        ];
    }
}