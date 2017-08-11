<?php

namespace App\Transformers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use League\Fractal\TransformerAbstract;
use App\Models\PointRecord;

/**
 * Class PointRecordListTransformer
 * @package App\Transformers
 */
class PointRecordListTransformer extends TransformerAbstract
{
    /**
     * @param PointRecord $data
     * @return array
     */
    public function transform(PointRecord $data)
    {
        return [
            'point' => $data->point,
            'type' => $this->typeName($data->type),
            'created_at' => $this->humanTime($data->created_at)
        ];
    }

    protected function humanTime(Carbon $time)
    {
        Carbon::setLocale('zh');
        if (Carbon::now() > Carbon::parse($time)->addDays(100)) {
            return $time;
        }
        return $time->diffForHumans();
    }

    protected function typeName($type)
    {
        $types = Config::get('service.package_exchange_status');
        if (in_array($type, array_keys($types))) {
            return $types[$type];
        } else {
            return '奖励';
        }
    }
}