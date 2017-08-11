<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\PointMoneyRecord;

/**
 * Class PointMoneyRecordingTransformer
 * @package App\Transformers
 */
class PointMoneyRecordingTransformer extends TransformerAbstract
{
    /**
     * @param PointMoneyRecord $data
     * @return array
     */
    public function transform(PointMoneyRecord $data)
    {
        return [
            'id' => $data->id,
            'consume_points' => $data->consume_points,
            'exchange_money' => $data->exchange_money / 100,
            'card_no' => $this->blurName($data->card_no, 'bottom', 4),
            'card_bank' => $data->card_bank,
            'card_owner' => $this->blurName($data->card_owner, 'top', 1),
            'card_owner_phone' => $this->blurName($data->card_owner_phone, 'both', 3),
            'status_name' => $this->statusName($data->status),
            'status' => $data->status
        ];
    }

    public function statusName($status)
    {
        return in_array($status, array_keys(PointMoneyRecord::STATUS)) ? PointMoneyRecord::STATUS[$status] : '';
    }

    // 模糊保留固定位置的位数，type: top, bottom, both; place: number
    public function blurName($word, $type, $place=0)
    {
        $len = mb_strlen($word);

        if ($len <= $place) {
            return str_repeat('*', $len);
        }

        switch ($type) {
            case 'top':
                $top = mb_substr($word, 0, $place);
                $str = $top.str_repeat('*', $len-$place);
                break;
            case 'bottom':
                $bottom = mb_substr($word, -1 * $place);
                $str = str_repeat('*', $len-$place).$bottom;
                break;
            case 'both':
                $top = mb_substr($word, 0, $place);
                $bottom = mb_substr($word, -1 * $place);
                $str = $top.str_repeat('*', $len-2*$place).$bottom;
                break;
            default:
                $top = mb_substr($word, 0, $place);
                $str = $top.str_repeat('*', $len-$place);
                break;
        }
        return $str;
    }
}