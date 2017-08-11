<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardImportRequest extends FormRequest
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
            'file_id' => 'required',
			'telecom_id' => 'required',
			'status' => 'required',
			'type' => 'required',
			'created_time' => 'required',
        ];
    }
}
