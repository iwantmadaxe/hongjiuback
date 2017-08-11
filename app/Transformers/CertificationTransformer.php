<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class CertificationTransformer
 * @package App\Transformers
 */
class CertificationTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'code' => $data->code,
			'username' => $data->username,
			'phone' => $data->phone,
			'status' => $data->status,
			'reason' => $data->reason,
			'front_image' => asset('storage/'.$data->front_image),
			'back_image' => asset('storage/'.$data->back_image),
		];
    }
}