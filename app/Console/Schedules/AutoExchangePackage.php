<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/8/8
 * Time: 上午11:35
 */

namespace App\Console\Schedules;

use App\Jobs\ExchangePackage;
use App\Models\PointPackageRecord;
use Carbon\Carbon;
use EasyWeChat\Support\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AutoExchangePackage
{
    use DispatchesJobs;

    public function __construct()
    {
        $this->handler();
    }

    public function handler()
    {
        $threeMinutesBefore = Carbon::now()->subMinutes(3);

        // 查询所有为已申请兑换套餐、且至少间隔在 3min 的订单
        $orders = PointPackageRecord::where('status', 1)
            ->where('updated_at', '<=', $threeMinutesBefore)->get();

        foreach ($orders as $k => $order) {
            //调用电信卡接口/卡/套餐
            try {
                Log::debug('开始计算兑换申请：'.$order->id);
                $this->dispatch(new ExchangePackage($order));
            } catch (\Exception $e) {
                logger($e);
            }
        }
    }
}