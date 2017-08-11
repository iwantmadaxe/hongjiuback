<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class AdminListTransformer
 * @package App\Transformers
 */
class AdminListTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {

        return [
        	'id' => $data->id,
			'username' => $data->username,
            'role_id' => $data->roles->first()['id'],
			'role' => $data->roles->first()['display_name'],
			'status' => $data->status,
		];
    }
}