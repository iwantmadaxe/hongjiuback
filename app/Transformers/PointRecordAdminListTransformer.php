<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\PointRecord;

/**
 * Class PointRecordAdminListTransformer
 * @package App\Transformers
 */
class PointRecordAdminListTransformer extends TransformerAbstract
{
    /**
     * @param PointRecord $data
     * @return array
     */
    public function transform(PointRecord $data)
    {
        return [
            'receiver_user' => $this->pureUser($data->receiverUser),
            'sponsor_user' => $this->pureUser($data->sponsorUser),
            'point' => $data->point,
            'type' => $this->typeName($data->type),
            'created_at' => $data->created_at->format('Y-m-d H:i:s')
        ];
    }

    protected function pureUser($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name
        ];
    }

    protected function typeName($type)
    {
        switch ($type) {
            case 1:
                return '购卡奖励';
                break;
            case 2:
                return '充值奖励';
                break;
            default:
                return '充值奖励';
                break;
        }
    }
}