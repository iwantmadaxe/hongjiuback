<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class PurchaseHistoryTransformer
 * @package App\Transformers
 */
class PurchaseHistoryTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'package_name' => $data->package_name,
			'package_price' => $data->package_price,
			'start_time' => $data->start_time,
			'end_time' => $data->end_time,
		];
    }
}