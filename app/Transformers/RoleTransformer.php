<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Role;

/**
 * Class RoleTransformer
 * @package App\Transformers
 */
class RoleTransformer extends TransformerAbstract
{
    /**
     * @param Role $data
     * @return array
     */
    public function transform(Role $data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'display_name' => $data->display_name,
            'description' => $data->description,
        ];
    }
}