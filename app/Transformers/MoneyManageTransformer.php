<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class MoneyManageTransformer
 * @package App\Transformers
 */
class MoneyManageTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'agent_id' => $data->agent_id,
			'name' => $data->agent->name,
			'balance' => $data->balance / 100,
			'all_money' => $data->all_money / 100,
			'username' => $data->admin->username,
			'created_at' => $data->admin->created_at->toDateTimeString(),
		];
    }
}