<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Card;

/**
 * Class UserCardTransformer
 * @package App\Transformers
 */
class UserCardTransformer extends TransformerAbstract
{
    /**
     * @param Card $data
     * @return array
     */
    public function transform(Card $data)
    {
        return [
            'id' => $data->id,
            'code' => $data->code,
            'nick_name' => $data->other_name
        ];
    }
}