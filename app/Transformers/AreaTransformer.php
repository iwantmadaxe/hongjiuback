<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class AreaTransformer
 * @package App\Transformers
 */
class AreaTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'code' => $data->code,
			'name' => $data->name,
		];
    }
}