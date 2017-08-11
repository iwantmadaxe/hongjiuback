<?php

namespace App\Transformers;

use App\Models\PointPackageRecord;
use League\Fractal\TransformerAbstract;


/**
 * Class PointPackageRecordingListTransformer
 * @package App\Transformers
 */
class PointPackageRecordingListTransformer extends TransformerAbstract
{
    /**
     * @param PointPackageRecord $data
     * @return array
     */
    public function transform(PointPackageRecord $data)
    {
        return [
            'user_id' => $data->user_id,
            'user_name' => $data->user ? $data->user->name : '',
            'package_id' => $data->package_id,
            'package_name' => $data->package ? $data->package->display_name : '',
            'card_no' => $data->card ? $data->card->code : '',
            'consume_points' => $data->consume_points,
            'status' => $data->status,
            'status_name' => $this->statusName($data->status),
            'created_at' => $data->created_at->toDateTimeString()
        ];
    }

    public function statusName($status)
    {
        switch ($status) {
            case 1:
                $info = '在处理';
                break;
            case 2:
                $info = '提交运营商成功';
                break;
            case 3:
                $info = '处理失败';
                break;
            default:
                $info = '处理失败';
                break;
        }
        return $info;
    }
}