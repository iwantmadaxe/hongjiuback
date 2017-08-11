<?php

namespace App\Jobs;

use App\Exceptions\TeleComException;
use App\Models\CardServiceLog;
use App\Models\Order;
use App\Services\TelecomPackageEvent\BaseEvent;
use Exception;
use App\Models\Card;
use App\Services\TelecomCard\TelecomCardManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class OrderPakage
 * @package App\Jobs
 */
class OrderPakage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


	protected $card_id;
	protected $package_id;
	protected $order;

    /**
     * OrderPakage constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->card_id = $order->card_id;
        $this->package_id = $order->package_id;
        $this->order = $order;
    }

    /**
     * Execute the job.
     * @param TelecomCardManager $telecomCardManager
     * @throws TeleComException
     */
    public function handle(TelecomCardManager $telecomCardManager)
    {
        $card = Card::find($this->card_id);
        $cardManager = $telecomCardManager->card($card);
        // 订套餐
        $res = $cardManager->orderPackage($this->package_id);
        if (!$res) {
            throw new TeleComException('开套餐失败', 400780);
        } else {
            $this->order->package_type = 1;
            $this->order->update();
        }
    }

    public function failed(Exception $e)
    {
        // 标记卡，和失败原因
        $log = json_encode(['message' => $e->getMessage()]);
        CardServiceLog::create(
            [
                'card_id' => $this->card_id,
                'log' => $log
            ]
        );
    }
}
