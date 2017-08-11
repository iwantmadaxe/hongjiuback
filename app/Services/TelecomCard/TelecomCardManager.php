<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Services\TelecomCard;

use App\Models\Card;
use App\Models\Package;
use App\Repositories\FlowRepository;
use App\Repositories\TelecomStatusRepository;
use Carbon\Carbon;

class TelecomCardManager
{
	private $type = [
		'month'    => 1,
		'season'   => 2,
		'halfYear' => 3,
		'year'     => 4,
	];

	private $cardHandler;

	protected $flowRepository;

	protected $telecomStatusRepository;

    public function __construct(FlowRepository $flowRepository, TelecomStatusRepository $telecomStatusRepository)
    {
        $this->flowRepository = $flowRepository;
        $this->telecomStatusRepository = $telecomStatusRepository;
    }

    public function card(Card $card)
	{
		$type = $card->type;

		switch ($type) {
			case $this->type['month'] :
				$this->cardHandler = app('App\Services\TelecomCard\MonthCard');
				break;
			case $this->type['season'] :
				$this->cardHandler = app('App\Services\TelecomCard\SeasonCard');
				break;
			case $this->type['halfYear'] :
				$this->cardHandler = app('App\Services\TelecomCard\SeasonCard');
				break;
			case $this->type['year'] :
				$this->cardHandler = app('App\Services\TelecomCard\SeasonCard');
				break;
		}

		$this->cardHandler->setCard($card);
		return $this;
	}

    /**
     * 获取实时的流量
     * @return array
     */
	public function flow()
	{
        $speed = 0;
	    $flow = $this->cardHandler->flow(); // 接口获取流量
        $card = $this->cardHandler->getCard(); // 数据表获取流量

        if ($card->flow()->first() && $card->flow()->first()->last_time) { // 计算平均流量使用速率
            $speed = ($card->flow->flow - $flow['remained']) / (time() - Carbon::parse($card->flow->last_time)->timestamp);
        }

        //计算流量余额 使用速度 以及更新level
        $this->flowRepository->updateFlow([
            'last_flow' => $flow['lastFlow'],
            'last_flow_time' => $flow['lastFlowTime'],
            'flow' => $flow['remained'],
            'total_flow' => $flow['total'],
            'used' => $flow['used'],
            'speed' => $speed,
            'level' => $this->updateLevel($flow['remained'], $speed),
        ], $card->id);

		return $flow;
	}

    /**
     * 获取流量，缓存一定时间
     * @return array
     */
    public function currentFlow()
    {
        $card = $this->cardHandler->getCard();
        //先看数据库中记录的更新时间  如果在10分钟之内 则直接返回	 || 看看电信是不是挂了  如果挂了直接返回
        if (!$this->flowRepository->needFreshData($card->id) || $this->serverDead()) {
            $flowInfo = $this->flowRepository->findBy('card_id', $card->id);
            $flow = [
                'total' => $flowInfo ? $flowInfo->total_flow : 0,
                'used'  => $flowInfo ? $flowInfo->used : 0,
                'remained' => $flowInfo ? $flowInfo->flow : 0,
                'lastFlow' => $flowInfo ? $flowInfo->last_flow : 0,
                'lastFlowTime' => $flowInfo ? $flowInfo->last_flow_time : Carbon::now()
            ];
        } else {
            $flowing = $this->flow();
            $flow = [
                'total' => $flowing['total'],
                'used'  => $flowing['used'],
                'remained' => $flowing['remained'],
                'lastFlow' => $flowing['lastFlow'],
                'lastFlowTime' => $flowing['lastFlowTime']
            ];
        }

        return $flow;
	}

    /**
     * 卡停机
     * @return mixed
     */
	public function stop()
	{
		return $this->cardHandler->disable();
	}

    /**
     * 卡复机
     * @return mixed
     */
	public function start()
	{
		return $this->cardHandler->enable();
	}

    /**
     * 激活卡
     * @return mixed
     */
	public function active()
	{
		return $this->cardHandler->active();
	}

    /**
     * 根据剩余流量和平均使用速率 将卡加入不同等级的实时监控
     * @param $flow
     * @param $speed
     * @return mixed
     */
    public function updateLevel($flow, $speed)
    {
        return $this->cardHandler->updateLevel($flow, $speed);
	}

    /**
     * 查询套餐是否需要关闭
     * @param $flow
     * @return mixed
     */
    public function checkForStop($flow)
    {
        return $this->cardHandler->checkForStop($flow);
	}

    /**
     * 判断电信服务器是否挂了
     * @return int
     */
    public function serverDead()
    {
        return $this->telecomStatusRepository->isDead();
	}

    /**
     * 删除卡
     * 停机 => 退套餐 => 删除卡
     */
    public function delete()
    {
        // 先停机
        $stoping = $this->stop();
        // 退套餐
        $backing = $this->cardHandler->backPackage();
        // 删除该卡
        if ($stoping && $backing) {
            $card = $this->cardHandler->getCard();
            $res = $card->delete();
            return !!$res;
        } else {
            return false;
        }
	}

    /**
     * 判断是否已经停机
     * @return mixed
     */
    public function stopped()
    {
        return $this->cardHandler->stopped();
	}

    /**
     * 退订套餐
     * @return mixed
     */
    public function backPackage()
    {
        return $this->cardHandler->backPackage();
	}

    /**
     * 订阅套餐
     * @param $packageId
     * @return mixed
     */
    public function orderPackage($packageId)
    {
        return $this->cardHandler->orderPackage($packageId);
	}

    /**
     * 更新卡状态
     * @return mixed
     */
    public function updateCardStatus()
    {
        return $this->cardHandler->updateCardStatus();
	}

    /**
     * 获取卡产品资料
     * @return mixed
     */
    public function cardProductInfo()
    {
        return $this->cardHandler->productInfo();
	}

    /**
     * 添加达量断网
     * @param $quota
     * @param $type
     * @return mixed
     */
    public function addNet($quota, $type)
    {
        return $this->cardHandler->addNet($quota, $type);
	}

    /**
     * 更新达量断网
     * @param $quota
     * @param $type
     * @return mixed
     */
    public function updateNet($quota, $type)
    {
        return $this->cardHandler->updateNet($quota, $type);
    }

    /**
     * 取消达量断网功能
     * @param $quota
     * @param $type
     * @return mixed
     */
    public function deleteNet($quota, $type)
    {
        return $this->cardHandler->deleteNet($quota, $type);
    }

    /**
     * 达量断网恢复上网
     * @return mixed
     */
    public function recoverNet()
    {
        return $this->cardHandler->recoverNet();
    }

    /**
     * 卡流量详单
     * @return mixed
     */
    public function detailFlowList()
    {
        return $this->cardHandler->detailFlowList();
    }

    /**
     * 开通积分退换套餐
     * @param $flowValue
     * @return mixed
     */
    public function exchangePackage($flowValue)
    {
        return $this->cardHandler->packageOrder($flowValue);
    }
}