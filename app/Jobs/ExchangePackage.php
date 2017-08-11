<?php

namespace App\Jobs;

use App\Exceptions\TeleComException;
use App\Models\CardServiceLog;
use App\Models\PointPackageRecord;
use App\Models\PointRecord;
use App\Services\TelecomCard\TelecomCardManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;

class ExchangePackage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $card;
    protected $packageInfo;
    protected $pointPackageRecord;

    /**
     * ExchangePackage constructor.
     * @param PointPackageRecord $pointPackageRecord
     */
    public function __construct(PointPackageRecord $pointPackageRecord)
    {
        $this->card = $pointPackageRecord->card()->first();
        $this->packageInfo = $pointPackageRecord->package()->first();
        $this->pointPackageRecord = $pointPackageRecord;
    }

    /**
     * @param TelecomCardManager $telecomCardManager
     * @throws TeleComException
     */
    public function handle(TelecomCardManager $telecomCardManager)
    {
        $cardManager = $telecomCardManager->card($this->card);
        // 开套餐
        $res = $cardManager->exchangePackage($this->packageInfo->flow_value);
        if (!$res) {
            throw new TeleComException('开套餐失败', 400780);
        } else {
            // 添加扣除积分记录
            PointRecord::create([
                'sponsor' => $this->card->user_id,
                'receiver' => $this->card->user_id,
                'point' => -1 * $this->packageInfo->points,
                'type' => 4,
                'pointable_id' => $this->pointPackageRecord->id,
                'pointable_type' => '\App\Models\PointPackageRecord'
            ]);

            // 将兑换变成通过
            $this->pointPackageRecord->status = 2;
            $this->pointPackageRecord->update();
        }
    }

    public function failed(Exception $e)
    {
        // 标记卡，和失败原因
        $log = json_encode(['message' => $e->getMessage()]);
        CardServiceLog::create(
            [
                'card_id' => $this->card->id,
                'log' => $log
            ]
        );
    }
}
