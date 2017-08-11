<?php

namespace App\Transformers;

use App\Models\Role;
use League\Fractal\TransformerAbstract;


/**
 * Class RoleListTransformer
 * @package App\Transformers
 */
class RoleListTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'name' => $data->display_name,
			'description' => $data->description,
			'num' => $this->num($data->id),
		];
    }

    private function num($id)
	{
		$role = Role::where('id', $id)->withCount('users')->first();
		return $role ? $role->users_count : 0;
	}
}