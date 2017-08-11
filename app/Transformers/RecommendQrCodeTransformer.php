<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Recommend;

/**
 * Class RecommendQrCodeTransformer
 * @package App\Transformers
 */
class RecommendQrCodeTransformer extends TransformerAbstract
{
    /**
     * @param Recommend $data
     * @return array
     */
    public function transform(Recommend $data)
    {
        return [
            'uuid' => $data->uuid,
            'url' => url($data->url),
            'points' => $data->points
        ];
    }
}