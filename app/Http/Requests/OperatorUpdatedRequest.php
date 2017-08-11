<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperatorUpdatedRequest extends FormRequest
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
			'account' => 'required',
			'password' => 'required',
			'key1' => 'required',
			'key2' => 'required',
			'key3' => 'required',
			'note' => 'required',
        ];
    }
}
