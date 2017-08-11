<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/8/1
 * Time: 下午1:13
 */

namespace App\Console\Schedules;


use App\Jobs\OrderPakage;
use App\Models\Order;
use Carbon\Carbon;
use EasyWeChat\Support\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AutoReopenPackage
{
    use DispatchesJobs;

    public function __construct()
    {
        $this->handler();
    }

    public function handler()
    {
        $threeMinutesBefore = Carbon::now()->subMinutes(3);
        // 查询所有为已成功支付、未成功开套餐、且至少间隔在3min的订单
        $orders = Order::where('status', 2)->where('package_type', 0)
            ->where('updated_at', '<=', $threeMinutesBefore)->get();

        foreach ($orders as $k => $order) {
            //调用电信卡接口/卡/套餐
            try {
                Log::debug('开始计算订单：'.$order->id);
                $this->dispatch(new OrderPakage($order));
            } catch (\Exception $e) {
                logger($e);
            }
        }
    }
}