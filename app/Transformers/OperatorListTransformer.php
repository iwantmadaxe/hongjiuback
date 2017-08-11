<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class OperatorListTransformer
 * @package App\Transformers
 */
class OperatorListTransformer extends TransformerAbstract
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
			'account' => $data->account,
			'password' => $data->password,
			'key1' => $data->key1,
			'key2' => $data->key2,
			'key3' => $data->key3,
			'note' => $data->note,
		];
    }
}