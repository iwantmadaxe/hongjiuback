<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCreateRequest extends FormRequest
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
            'username' => 'required|unique:admins,username',
			'password' => 'required',
			'role_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '用户名必填',
            'username.unique' => '该用户名已经存在',
            'password.required' => '密码必填',
            'role_id.required' => '角色必选',
        ];
    }
}
