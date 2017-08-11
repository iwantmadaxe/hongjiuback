<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class AgentListTransformer
 * @package App\Transformers
 */
class AgentListTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			//'password' => $data->password,
			'name' => $data->agent ? $data->agent->name : '',
            'parent_agent' => $data->agent ? ($data->agent->parentAgent ? $data->agent->parentAgent->name : '') : '',
            'grandparent_agent' => $data->agent ? ($data->agent->parentAgent ? ($data->agent->parentAgent->parentAgent ? $data->agent->parentAgent->parentAgent->name : '') : '') : '',
            'app_id' => $data->agent ? $data->agent->app_id : '',
			'app_secret' => $data->agent? $data->agent->app_secret : '',
			'key' => $data->agent ? $data->agent->key : '',
			'token' => $data->agent ? $data->agent->token : '',
			'merchant' => $data->agent ? $data->agent->merchant : '',
			'discount' => $data->agent ? $data->agent->discount : '',
			'seal_discount' => $data->agent ? $data->agent->seal_discount : '',
			'username' => $data->username,
			'has_wechat' => $data->agent ? $data->agent->has_wechat : '',
			'created_at' => $data->created_at,
		];
    }
}