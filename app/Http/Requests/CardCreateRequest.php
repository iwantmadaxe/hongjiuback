<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardCreateRequest extends FormRequest
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
        	'code' => 'required|unique:cards,code',
			'acc_number' => 'required|unique:cards,acc_number',
			'iccid' => 'required|unique:cards,iccid',
			'telecom_id' => 'required',
			'status' => 'required',
			'type' => 'required',
			'created_time' => 'required',
        ];
    }
}
