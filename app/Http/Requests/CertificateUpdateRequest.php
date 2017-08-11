<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificateUpdateRequest extends FormRequest
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
            'status' => 'required|numeric',
			'reason' => 'required_if:status,3',
        ];
    }

    public function messages()
    {
        return [
            'status.required' => '审核状态必选',
            'status.numeric' => '审核状态必选',
            'reason.required_if' => '未审核通过理由必选'
        ];
    }
}
