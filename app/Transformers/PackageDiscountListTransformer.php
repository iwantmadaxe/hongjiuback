<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class PackageDiscountListTransformer
 * @package App\Transformers
 */
class PackageDiscountListTransformer extends TransformerAbstract
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
			'agent_name' => $data->agent ? ($data->agent->agent ? $data->agent->agent->name : '非代理商') : '非代理商',
			'package_id' => $data->package_id,
			'package_name' => $data->package->name,
			'discount' => $data->discount . '%',
			'package_price' => $data->package->price / 100,
			'seal_price' => $data->package->price * (int)$data->discount / 10000,
		];
    }
}