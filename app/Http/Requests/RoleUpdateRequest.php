<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
        $id = $this->route()->parameter('id');
        return [
            'name' => 'required|unique:roles,name,'.$id.',id',
            'display_name' => 'required',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '角色英文名必填',
            'name.unique' => '角色英文名已被占用',
            'display_name.required' => '角色中文名必填',
            'description.required' => '角色描述必填'
        ];
    }
}
