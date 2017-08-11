<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class MessageTransformer
 * @package App\Transformers
 */
class MessageTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'data' => $data->data,
		];
    }
}