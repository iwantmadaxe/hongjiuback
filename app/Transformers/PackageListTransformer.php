<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class PackageListTransformer
 * @package App\Transformers
 */
class PackageListTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'production_id' => $data->flow_value,
			'flow' => $data->flow,
			'name' => $data->name,
			'display_name' => $data->display_name,
			'price' => round ($data->price / 100, 2),
			'type' => $data->type,
			'instruction' => $data->instruction,
			'status' => $data->status,
			'is_back' => $data->is_back,
			'is_apart' => $data->is_apart,
            'is_exchange' => $data->is_exchange,
            'points' => $data->points,
		];
    }
}