<?php

namespace App\API\V1\Requests;

use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class SendSmsRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
		return [
			'phone' => 'required|'.$this->phone,
			'type' => 'required|'.Rule::in(Config::get('service.sms_type')),
		];
    }
}
