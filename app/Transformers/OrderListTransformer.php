<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;


/**
 * Class OrderListTransformer
 * @package App\Transformers
 */
class OrderListTransformer extends TransformerAbstract
{
    /**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'user_id' => $data->user_id,
			'money_agent' => $data->money_agent,
			'card_code' => $data->card ? $data->card->code : '',
			'card_agent' => $data->card_agent,
			'agent_name' => $data->agent_name,
			'package_name' => $data->orderPackage ? $data->orderPackage->package_name : '',
			'package_price' => $data->orderPackage ? $data->orderPackage->package_price : '',
			'cut_discount' => $data->cut_discount,
			'cut_price' => $data->cut_price / 100,
			'over_bill' => $data->overdue_bill / 100,
			'favourable_price' => $data->favourable_price / 100,
			'prepayment_fee' => $data->prepayment_fee / 100,
			'total_fee' => $data->total_fee / 100,
			'code' => $data->code,
			'created_at' => $data->created_at,
			'is_back' => $data->is_back,
            'package_type' => $data->package_type,
            'status' => $this->statusName($data->status),
            'address' => $data->address ? $this->addressName($data->address) : []
		];
    }

    public function statusName($status)
    {
        $statusType = [
            '1' => '未支付',
            '2' => '已支付'
        ];
        return $statusType[$status] ?:'';
    }

    public function addressName($address)
    {
        return (new AddressListTransformer())->transform($address);
    }
}