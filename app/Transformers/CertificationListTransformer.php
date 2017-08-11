<?php

namespace App\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;


/**
 * Class CertificationListTransformer
 * @package App\Transformers
 */
class CertificationListTransformer extends TransformerAbstract
{
	private $status = [
		1 => '未审核',
		2 => '审核通过',
		3 => '审核未通过',
	];

    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'code' => $data->code,
			'username' => $data->username,
			'phone' => $data->phone,
			'status' => $this->status[$data->status],
			'reason' => $data->reason,
			'created_at' => Carbon::parse($data->created_at)->toDateString(),
			'checked_at' => Carbon::parse($data->checked_at)->toDateString(),
		];
    }
}