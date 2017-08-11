<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Permission;

/**
 * Class PermissionTransformer
 * @package App\Transformers
 */
class PermissionTransformer extends TransformerAbstract
{
    /**
     * @param Permission $data
     * @return array
     */
    public function transform(Permission $data)
    {
        return [
            'id' => $data->id,
            'display_name' => $data->display_name,
            'description' => $data->description,
            'group_name' => $data->group_name
        ];
    }
}