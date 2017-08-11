<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class AdminMessageTransformer
 * @package App\Transformers
 */
class AdminMessageTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
		];
    }
}