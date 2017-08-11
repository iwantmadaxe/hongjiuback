<?php

namespace App\Transformers;

use App\Repositories\AreaRepository;
use League\Fractal\TransformerAbstract;


/**
 * Class UserProfileTransformer
 * @package App\Transformers
 */
class UserProfileTransformer extends TransformerAbstract
{
	private $areaRepository;

	public function __construct(AreaRepository $areaRepository)
	{
		$this->areaRepository = $areaRepository;
	}

	/**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        $recommend = $data->recommendation()->first();
        return [
        	'id' => $data->id,
			'name' => $data->name,
			'email' => $data->email,
			'phone' => $data->phone,
			'address' => $data->address,
			'area' => $this->area($data->area_code),
            'points' => $recommend ? $recommend->points : 0,
            'qrcode' => $recommend ? ($recommend->url?url($recommend->url): '') : ''
		];
    }

    private function area($area_code)
	{
		return $this->areaRepository->pathByCode($area_code);
	}
}