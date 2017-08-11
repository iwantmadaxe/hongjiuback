<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/25
 * Time: 上午11:51
 */

namespace App\Services\TelecomOrder;


use App\Exceptions\OrderException;
use App\Models\AgentInfo;

class BuyCardOrder extends OrderContract
{
    /**
     * 推荐人
     * @var
     */
    protected $recommender = null;

    /**
     * 设置总价
     * @return $this
     */
    public function calculateTotalFee()
    {
        $this->totalFee = $this->unitPrice * $this->amount;
        return $this;
    }

    /**
     * 生成支付订单
     * @param $userId
     * @param AgentInfo $agent
     * @param null $card
     * @return array
     * @throws OrderException
     */
    public function order($userId, $agent=null, $card=null)
    {
        $this->checkBase();
        $this->calculateTotalFee();

        $order = [
            'code' => $this->generateCode(),
            'user_id' => $userId,
            'address_id' => $this->address?:0,
            'card_agent' => $agent ? $agent['id'] : '',
            'money_agent' => $agent ? $agent['id'] : '',
            'agent_name' => $agent ? $agent['name'] : '',
            'package_id' => $this->packageInfo->id,
            'amount' => $this->amount,
            'total_fee' => $this->totalFee,
            'out_trade_no' => date('YmdHms'),
            'status' => 1,     //未支付状态
            'package_type' => 2,
        ];

        if ($this->recommender) {
            $order['recommender'] = $this->recommender;
        }

        if ($order = $this->orderRepository->create($order)) {     //等待发起微信支付
            return $order;
        } else {
            throw new OrderException('创建订单失败', 400951);
        }
    }

    /**
     * 检查必要参数
     */
    public function checkBase()
    {
        if (!$this->packageInfo) {
            throw new \InvalidArgumentException('未提供正确的套餐参数！');
        }

        if (!$this->address) {
            throw new \InvalidArgumentException('未提供正确的地址参数！');
        }
    }

    /**
     * 设置推荐人
     * @param $recommender
     * @return $this
     */
    public function setRecommender($recommender)
    {
        $this->recommender = $recommender;
        return $this;
    }
}