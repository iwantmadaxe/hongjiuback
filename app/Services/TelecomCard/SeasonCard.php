<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Services\TelecomCard;

use App\Models\CardPackage;
use App\Models\Package;
use Carbon\Carbon;
use App\Models\AgentInfo;
use App\Exceptions\TeleComException;

class SeasonCard extends TelecomCard
{
    /**
     * 季卡流量查询
     * @return array
     */
    public function flow()
    {
        //获取套餐累计总量，以及套餐们的开始日期
        $packages = $this->tariffPackage->package($this->card);
        $cumulationTotal = $packages['cumulationTotal']; // 可用的总流量
        $startDate = $packages['startDate']; // 套餐流量计算的起始日期

        //获取套餐们的开始日期到今天的流量使用量
        //如果涉及到跨月的情况, 则先从数据库查找本月前个月的流量使用量
        $historyFlow = $this->getHistoryFlow($startDate);

        $endMonthflow = $this->flowApi->date($this->card, Carbon::now()->startOfMonth()->format('Ymd'), Carbon::now()->format('Ymd'));
        $endMonthflow = $endMonthflow['flow'];    //最后一个月的使用流量 单位为M

        return [
            'lastFlow' => $historyFlow['flow'],
            'lastFlowTime' => $historyFlow['lastMonth'],
            'total' => round($cumulationTotal / 1024, 6),
            'used'  => $endMonthflow + $historyFlow['flow'],
            'remained' => round(($cumulationTotal / 1024) - $endMonthflow - $historyFlow['flow'], 6),
        ];
    }

    /**
     * 停机保号操作
     * @param $flow
     * @return bool
     */
    public function disable($flow = 0)
    {
        $res = $this->cardApi->disable($this->card);
        if ($res['code'] == 0) {
            // 判断是否欠费，欠费需要设置欠费
            $flow = $flow ?:($this->card->flow ? $this->card->flow->flow : 0);
            if ($flow < 0) {
                $this->overdueBill = $this->overBill($flow);
                $this->card->update(['overdue_bill' => $this->overdueBill]);
            }
            if ($flow) {
                $this->card->flow()->update(['flow' => 0]); // 清空流量
            }
            $this->cardRepository->updateStatus($this->card->id, 2);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 复机操作
     * @return bool
     * @throws TeleComException
     */
    public function enable()
    {
        // 判断复机时是否补齐钱
        if ($this->card->overdue_bill > 0) {
            $money = round($this->card->overdue_bill, 2);
            throw new TeleComException("请补交{$money}元，才能开通", 400900);
        }

        $res = $this->cardApi->enable($this->card);
        if ($res['code'] == 0) {
            $this->cardRepository->updateStatus($this->card->id, 1);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 月卡激活
     * @return bool
     */
    public function active()
    {
        $res = $this->cardApi->active($this->card);
        if ($res['code'] == 0) {
            $this->cardRepository->updateStatus($this->card->id, 1);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 卡退套餐
     * @return bool
     */
    public function backPackage()
    {
        $packageInfo = $this->card->package()->first();
        // 未订阅套餐，可直接过
        if (!$packageInfo) {
            return true;
        }
        $packageInfoReal = $this->card->package()->first()->package()->withTrashed()->first();
        if (!$packageInfoReal) {
            // 直接删了该套餐
            $this->card->package()->first()->delete();
            return true;
        }

        // 欠费也会直接删除！！！
        $res = $this->tariffPackage->unsubscribe($this->card, $packageInfoReal->flow_value);

        if ($res['code'] == 0) {
            $this->card->package()->first()->delete();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 卡订阅套餐
     * @param $packageId
     * @return bool
     */
    public function orderPackage($packageId)
    {
        // 判断是否停机，停机先复机
        if ($this->stopped()) {
            $this->enable();
        }

        // 退订前一次的套餐
        $resBack = $this->backPackage();

        // 订新套餐，设置套餐过期时间
        if ($resBack) {
            $packageInfo = Package::find($packageId);
            $expiration = Carbon::now()->addMonth()->endOfMonth();

            $res = $this->tariffPackage->order($this->card, $packageInfo->flow_value);

            if ($res['code'] == 0) {
                // 保存套餐情况
                CardPackage::create([
                    'card_id' => $this->card->id,
                    'package_id' => $packageId,
                    'expiration' => $expiration
                ]);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}