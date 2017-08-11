<?php

namespace App\Listeners;

use App\Events\AddPointsEvent;
use App\Models\Order;
use App\Models\PointRecord;
use App\Models\Recommend;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddPointsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AddPointsEvent  $event
     * @return void
     */
    public function handle(AddPointsEvent $event)
    {
        // 推荐好友(type=1): 推荐人200分，好友订购流量(type=2): 本人、上级1元2分，上上级1元1积分
        switch ($event->type) {
            case 1: {
                // 推荐者
                $order = Order::find($event->id);
                if ($event->receiver) {
                    if ($event->receiver->recommendation()->first()) {
                        $event->receiver->recommendation()->first()->increment('points', 200 * $order->amount);
                    } else {
                        Recommend::create(['points' => 200 * $order->amount, 'user_id' => $event->receiver['id']]);
                    }
                    // 奖励记录
                    PointRecord::create([
                        'sponsor' => $event->sponsor['id'],
                        'receiver' => $event->receiver['id'],
                        'type' => $event->type,
                        'point' => 200 * $order->amount,
                        'pointable_id' => $event->id,
                        'pointable_type' => '\App\Models\Order'
                    ]);
                }

                break;
            }
            case 2: {
                // 判断recommend存在否，存在直接加，否则创建
                $pointsTop = floor($event->price / 100) * 2;
                $pointsSec = floor($event->price / 100) * 1;

                // 有推荐者
                if ($event->receiver) {
                    // 一级推荐者
                    if ($event->receiver->recommendation()->first()) {
                        $event->receiver->recommendation()->first()->increment('points', $pointsTop);
                    } else {
                        Recommend::create(['points' => $pointsTop, 'user_id' => $event->receiver['id']]);
                    }
                    // 推荐奖励
                    PointRecord::create([
                        'sponsor' => $event->sponsor['id'],
                        'receiver' => $event->receiver['id'],
                        'type' => $event->type,
                        'point' => $pointsTop,
                        'pointable_id' => $event->id,
                        'pointable_type' => '\App\Models\Order'
                    ]);

                    // 二级推荐者
                    $secondRecommender = $event->receiver->recommendation()->first()->recommender()->first();
                    if ($secondRecommender) {
                        if ($secondRecommender->recommendation()->first()) {
                            $secondRecommender->recommendation()->first()->increment('points', $pointsSec);
                        } else {
                            Recommend::create(['points' => $pointsSec, 'user_id' => $secondRecommender['id']]);
                        }
                        // 推荐奖励
                        PointRecord::create([
                            'sponsor' => $event->receiver['id'],
                            'receiver' => $secondRecommender['id'],
                            'type' => $event->type,
                            'point' => $pointsSec,
                            'pointable_id' => $event->id,
                            'pointable_type' => '\App\Models\Order'
                        ]);
                    }

                }

                // 本人
                if ($event->sponsor->recommendation()->first()) {
                    $event->sponsor->recommendation()->first()->increment('points', $pointsTop);
                } else {
                    Recommend::create(['points' => $pointsTop, 'user_id' => $event->sponsor['id']]);
                }
                // 充值奖励
                PointRecord::create([
                    'sponsor' => $event->sponsor['id'],
                    'receiver' => $event->sponsor['id'],
                    'type' => $event->type,
                    'point' => $pointsTop,
                    'pointable_id' => $event->id,
                    'pointable_type' => '\App\Models\Order'
                ]);
                break;
            }
        }
    }
}
