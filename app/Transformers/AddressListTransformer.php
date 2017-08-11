<?php

namespace App\Transformers;

use App\Models\Area;
use League\Fractal\TransformerAbstract;
use App\Models\DeliveryAddress;

/**
 * Class AddressListTransformer
 * @package App\Transformers
 */
class AddressListTransformer extends TransformerAbstract
{
    /**
     * @param DeliveryAddress $data
     * @return array
     */
    public function transform(DeliveryAddress $data)
    {
        return [
            'id' => $data->id,
            'receiver' => $data->receiver,
            'contact' => $data->contact,
            'area' => $this->areaList($data->area),
            'address' => $data->address,
            'default' => $data->default
        ];
    }

    public function areaList($subArea)
    {
        $next = Area::where('code', $subArea)->first();
        $areas = [];
        while ($next) {
            $areas[] = [
                'id' => $next->id,
                'code' => $next->code,
                'name' => $next->name
            ];
            $next = $next->area()->first();
        }

        return array_reverse($areas);
    }
}