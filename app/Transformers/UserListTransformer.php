<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class UserListTransformer
 * @package App\Transformers
 */
class UserListTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'name' => $data->name,
			'email' => $data->email,
			'phone' => $data->phone,
            'points' => $data->recommendation ? intval($data->recommendation->points) : 0,
			'is_forbidden' => $data->is_forbidden,
		];
    }
}