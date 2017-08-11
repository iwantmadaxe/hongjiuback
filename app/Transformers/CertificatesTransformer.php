<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class CertificatesTransformer
 * @package App\Transformers
 */
class CertificatesTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'card_code' => $data->code,
			'status' => $data->status,
			'reason' => $this->reason($data->reason),
		];
    }

    private function reason($res)
	{
		if (!$res) return '';
		$reason = [
			'1' => '身份证和卡不同框',
			'2' => '图片不清晰',
			'3' => '图片疑似PS处理',
			'4' => '所填信息与证件不符',
			'5' => '无效图片',
		];
		return $reason[$res];
	}
}