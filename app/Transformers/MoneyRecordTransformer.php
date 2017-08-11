<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class MoneyRecordTransformer
 * @package App\Transformers
 */
class MoneyRecordTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'money' => $data->money,
			'order_id' => $data->order_id,
			'agent_id' => $data->agent_id,
            'agent_name' => $data->agent ? $data->agent->agent->name : '',
			'operator_id' => $data->operator_id,
			'operator_name' => $data->fromAgent ? $data->fromAgent->username : '',
			'type' => $data->type,
		];
    }
}