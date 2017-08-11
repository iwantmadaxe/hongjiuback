<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Api\V1\Controllers;

use App\API\V1\BaseController;
use App\Events\AddPointsEvent;
use App\Jobs\OrderPakage;
use App\Models\AgentInfo;
use App\Models\Card;
use App\Models\Money;
use App\Models\MoneyRecord;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\User;
use App\Notifications\OrderPaied;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class WechatController extends BaseController
{
	public function notify(Request $request, $wechat)
	{
		$agent = AgentInfo::where('user_id', $wechat)->first();
		$options = [
			// 前面的appid什么的也得保留哦
			'app_id' => $agent->app_id,
			'app_secret' => $agent->app_secret,
			// payment
			'payment' => [
				'merchant_id'        => $agent->merchant,
				'key'                => $agent->key,
			],
		];
		$app = new Application($options);
		$response = $app->payment->handleNotify(function($notify, $successful) use ($wechat){

			$order = Order::where('out_trade_no', $notify->out_trade_no)->first();

            if (!$order) {
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

			if ($order->pay_at) { //已支付
				return true; // 已经支付成功了就不再更新了
			}

			if ($successful) { // 不是已经支付状态则修改为已经支付状态
                $user = User::find($order->user_id)->first();
                $isBuyCard = $order->isBuyCard(); // 判断是不是买卡
                if ($isBuyCard) {
                    // 加积分
                    $receiverId = $order->recommender ?:null;
                    $receiver = null;
                    if ($receiverId) {
                       $receiver = User::find($receiverId);
                    }
                    event(new AddPointsEvent($receiver, $user, 1, 0, $order->id));
                } else { // 不是买卡 = 买套餐
                    $cutInfo = json_decode($order->agent_cut_info, true);

                    // 记录层层扣款
                    foreach ($cutInfo as $k => $v) {
                        //扣微信号的代理商的钱
                        $money = Money::where('agent_id', $v['id'])->first();
                        $money->decrement('balance', $v['price']);

                        MoneyRecord::create([
                            'type' => 1,
                            'agent_id' => $v['id'],
                            'money' => $v['price'],
                            'order_id' => $order->id
                        ]);
                    }

                    // 清空欠款
                    Card::where('id', $order->card_id)->update(['overdue_bill' => 0]);

                    //调用电信卡接口/卡/套餐
                    try {
                        $this->dispatch(new OrderPakage($order));
                    } catch (\Exception $e) {
                        logger($e);
                    }

                    // 加积分
                    $recommendation = $user->recommendation()->first();
                    $receiver = null;
                    if ($recommendation) {
                        $receiver = $recommendation->recommender()->first();
                    }
                    event(new AddPointsEvent($receiver, $user, 2, $order->total_fee, $order->id));
                }

                $packageInfo = Package::find($order->package_id);
				OrderPackage::create([
					'user_id' => $order->user_id,
					'package_id' => $order->package_id,
					'order_id' => $order->id,
					'package_name' => $packageInfo ? $packageInfo->name : '',
					'package_price' => $packageInfo ? $packageInfo->price : '',
					'package_type' => $packageInfo ? $packageInfo->type : '',
					'start_time' => Carbon::now()->toDateTimeString(),
					'end_time' => Carbon::now()->toDateTimeString(),
				]);

				//通知
				$user->notify(new OrderPaied($order));

				$order->pay_at = Carbon::now()->toDateTimeString(); // 更新支付时间为当前时间
				$order->status = 2;
			} else { // 用户支付失败
				$order->status = 3;
			}
			$order->save(); // 保存订单

			return true; // 返回处理完成
		});
		return $response;
	}
}