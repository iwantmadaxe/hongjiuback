<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Services\TelecomCard;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Card;
use App\Services\TelecomApi\Card as CardApi;
use App\Services\TelecomApi\Flow;
use App\Repositories\CardRepository;
use App\Repositories\PackageRepository;
use App\Services\TelecomApi\TariffPackage;

abstract class TelecomCard
{
    /**
     * 套餐类型
     */
    const TYPE = [
        'month' => 1,
        'season' => 2,
        'halfYear' => 3,
        'year' => 4,
    ];

    /**
     * 超出部分，每兆0.012元，要封顶
     */
    const OVER_BILL = 1.2;
    const BILL_TOP = 24 * 100;

    /**
     * @var Card
     */
    protected $card;

    protected $user;

    protected $cardType;

    protected $backPackaged = false; // 是否退套餐
    protected $apartPackaged = false; // 是否拆包

    protected $overdueBill = 0;    //欠费数额

    protected $packageType;

    protected $flowApi;

    protected $cardApi;

    protected $tariffPackage;

    protected $cardRepository;

    protected $packageRepository;

    public function __construct(User $user, PackageRepository $packageRepository,
                                CardRepository $cardRepository, TariffPackage $tariffPackage,
                                Flow $flowApi, CardApi $cardApi)
    {
        $this->user = $user;
        $this->flowApi = $flowApi;
        $this->cardApi = $cardApi;
        $this->tariffPackage = $tariffPackage;
        $this->cardRepository = $cardRepository;
        $this->packageRepository = $packageRepository;
    }

    public function setCard(Card $card)
    {
        $this->card = $card;

        // 设置套餐选项
        if ($card->package()->first() && $cardInfo = $card->package()->first()->package()->first()) {
            $this->cardType = $cardInfo->type;
            $this->backPackaged = $cardInfo->is_back;
            $this->apartPackaged = $cardInfo->is_apart;
        }
    }

    public function getCard()
    {
        return $this->card;
    }

    /**
     * 获取最新卡状态
     * @return array
     */
    public function updateCardStatus()
    {
        $cardStatus = $this->cardApi->status($this->card);
        if (in_array($cardStatus['status'], array_keys($this->cardRepository->status))) {
            $this->cardRepository->updateStatus($this->card->id, $cardStatus['status']);
        } else {
            $this->cardRepository->updateStatus($this->card->id, 14);
        }
        return $cardStatus;
    }

    /**
     * 获取卡产品资料
     * @return array
     */
    public function productInfo()
    {
        return $this->cardApi->productInfo($this->card);
    }

    /**
     * 卡流量详单
     * @return array
     */
    public function detailFlowList()
    {
        return $this->flowApi->month($this->card, 1);
    }
    
    protected function stopped()
    {
        return ($this->card->status != 1);
    }

    /**
     * 超出部分结算单位价格，返回分为单位
     * @return int
     */
    public function overBill($flow)
    {
        $bill = abs(self::OVER_BILL * $flow);
        $bill = $bill > self::BILL_TOP ? self::BILL_TOP : $bill;
        return $bill;
    }

    /**
     * 卡的套餐是否已经到期
     * 到期与否会影响的因素是: 若到期,则复机后新的套餐从上次到期那天开始算,且不用退订,套餐会自动延续
     * 否则复机后要退订之前的套餐,并订购新的套餐,且从生效那天开始往后算
     *
     * @param $cardId
     * @return bool
     */
    protected function isOverdue($cardId)
    {
        $card = $this->cardRepository->find($cardId);
        $expiration = $card->package->expiration;

        return Carbon::now()->toDateTimeString() > $expiration;
    }

    /**
     * 根据剩余流量和平均使用速率 将卡加入不同等级的实时监控
     * @param $flow
     * @param $speed
     * @return int
     */
    public function updateLevel($flow, $speed)
    {
        if ($speed) {    //使用速度为0的时候 不加入实时监控
            $time = ($flow / $speed);
        } else {
            return 0;
        }

        /*
         * ============================
         * 剩余流量换算时间
         * 时间在1d以上 采用天0
         * 时间在1h以上 采用小时2
         * 时间在1h以内，采用分钟
         * ============================
         */
        if ($time < 0) {
            return 0;
        } elseif ($time > 60*60*24) {
            return 0;
        } elseif ($time > 60*60) {
            return 2;
        } else {
            return 1;
        }
    }

    /**
     * 检查是否需要停机
     * @param $flow
     * @return bool
     */
    public function checkForStop($flow)
    {
        $flag = false;
        // 流量负的需要停机
        if ($flow < 0) {
            $flag = true;
        } else {
            // 超过有效时间需要停机
            $packageInfo = $this->card->package()->first();
            if ($packageInfo && $packageInfo->expiration <= Carbon::now()) {
                $flag = true;
            }
        }

        if ($flag) {
            $this->disable($flow); // 停机
        }

        return $flag;
    }

    /**
     * 获取历史流量数据
     * @param $startDate
     * @return array
     */
    protected function getHistoryFlow($startDate) {
        $now = Carbon::now()->startOfMonth();
        $flow = $this->card->flow()->first();
        $startMonth = $startDate ? Carbon::parse($startDate)->setTimezone('PRC')->startOfMonth() : Carbon::now()->startOfMonth();
        $lastMonth = $flow ? $flow->last_flow_time : Carbon::now()->startOfMonth();
        $calculateMonth = $lastMonth >= $startMonth ? $lastMonth : $startMonth;

        $flowed = $flow ? $flow->last_flow : 0;

        // 如果历史月份数据存在则直接输出即可
        if ($lastMonth && $lastMonth == Carbon::now()->subMonth()) {
            return [
                'flow' => $flowed,
                'lastMonth' => $lastMonth ?: null
            ];
        }

        while ($calculateMonth && $now > $calculateMonth) {
            $curflow = $this->flowApi->date($this->card, $calculateMonth->startOfMonth()->format('Ymd'), $calculateMonth->endOfMonth()->format('Ymd'));
            $curflow = $curflow['flow'];    // 单位为M
            $calculateMonth->startOfMonth()->addMonth();
            $flowed += $curflow;
        }

        return [
            'flow' => $flowed,
            'lastMonth' => $calculateMonth ? $calculateMonth->subMonth() : null
        ];
    }

    /**
     * 添加达量断网
     * @param $quota
     * @param $type
     * @return array
     */
    public function addNet($quota, $type)
    {
        return $this->cardApi->addNet($this->card, $quota, $type);
    }

    /**
     * 更新达量断网
     * @param $quota
     * @param $type
     * @return array
     */
    public function updateNet($quota, $type)
    {
        return $this->cardApi->updateNet($this->card, $quota, $type);
    }

    /**
     * 取消达量断网功能
     * @param $quota
     * @param $type
     * @return array
     */
    public function deleteNet($quota, $type)
    {
        return $this->cardApi->deleteNet($this->card, $quota, $type);
    }

    /**
     * 达量断网恢复上网
     * @return array
     */
    public function recoverNet()
    {
        return $this->cardApi->recoverNet($this->card);
    }

    /**
     * 开通套餐
     * @param $flowValue
     * @return array
     */
    public function packageOrder($flowValue)
    {
        return $this->tariffPackage->order($this->card, $flowValue);
    }

    /**
     * 实时查流量
     * @return mixed
     */
    abstract public function flow();

    /**
     * 停机断网。
     * @param $flow
     * @return mixed
     */
    abstract public function disable($flow = 0);

    /**
     * 停机保号时复机。
     * @return mixed
     */
    abstract public function enable();

    /**
     * 激活卡。
     * @return mixed
     */
    abstract public function active();

    /**
     * 退订套餐。
     * @return mixed
     */
    abstract public function backPackage();

    /**
     * 订阅套餐。
     * @param $packageId
     * @return mixed
     */
    abstract public function orderPackage($packageId);
}