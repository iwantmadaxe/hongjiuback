<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;
use App\Models\PointMoneyRecord;

/**
 * Class PointMoneyRecordingListTransformer
 * @package App\Transformers
 */
class PointMoneyRecordingListTransformer extends TransformerAbstract
{
    /**
     * @param PointMoneyRecord $data
     * @return array
     */
    public function transform(PointMoneyRecord $data)
    {
        return [
            'id' => $data->id,
            'user' => $this->userProfile($data->user),
            'consume_points' => $data->consume_points,
            'exchange_money' => $data->exchange_money / 100,
            'card_no' => $data->card_no,
            'card_bank' => $data->card_bank,
            'card_owner' => $data->card_owner,
            'card_owner_phone' => $data->card_owner_phone,
            'status_name' => $this->statusName($data->status),
            'status' => $data->status
        ];
    }

    public function statusName($status)
    {
        return in_array($status, array_keys(PointMoneyRecord::STATUS)) ? PointMoneyRecord::STATUS[$status] : '';
    }

    public function userProfile(User $data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name
        ];
    }
}