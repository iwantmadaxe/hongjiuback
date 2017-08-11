<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentCreateRequest extends FormRequest
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
            'name' => 'required',
			'username' => 'required|unique:admins,username',
			//'password' => 'required',
			'discount' => 'required',
			'seal_discount' => 'required',
			'has_wechat' => 'required|in:0,1',
			'app_id' => 'required_if:has_wechat,1',
			'app_secret' => 'required_if:has_wechat,1',
			'merchant' => 'required_if:has_wechat,1',
			'key' => 'required_if:has_wechat,1',
			'token' => 'required_if:has_wechat,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '真是姓名必填',
            'username.required' => '用户名必填',
            'username.unique' => '该用户名已经存在',
            'discount.required' => '代理商折扣必填',
            'seal_discount.required' => '出售折扣必填',
            'has_wechat.required' => '是否有微信平台必选',
            'has_wechat.in' => '是否有微信平台必选',
            'app_id.required_if' => '微信平台app_id必选',
            'app_secret.required_if' => '微信平台app_secret必选',
            'merchant.required_if' => '微信平台merchant必选',
            'key.required_if' => '微信平台merchant_key必选',
            'token.required_if' => '微信平台token必选',
        ];
    }
}
