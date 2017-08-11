<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class PointToPackageListTransformer
 * @package App\Transformers
 */
class PointToPackageListTransformer extends TransformerAbstract
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
            'points' => $data->points,
            'type' => $data->type,
            'instruction' => $data->instruction,
            'status' => $data->status,
            'is_back' => $data->is_back,
            'is_apart' => $data->is_apart,
        ];
    }
}