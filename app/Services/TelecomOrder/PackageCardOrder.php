<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/25
 * Time: 下午2:31
 */

namespace App\Services\TelecomOrder;


use App\Exceptions\OrderException;
use App\Models\AgentInfo;
use App\Models\Card;
use App\Models\Money;
use Carbon\Carbon;

class PackageCardOrder extends OrderContract
{
    /**
     * 欠费
     * @var int
     */
    protected $overBillFee = 0;

    /**
     * 折后价
     * @var int
     */
    protected $discountedFee = 0;

    /**
     * 提成费用
     * @var int
     */
    protected $cutPriceFee = 0;

    /**
     * 套餐预付费
     * @var int
     */
    protected $prePaymentFee = 0;

    /**
     * @var AgentInfo
     */
    protected $agent;

    /**
     * @var Card
     */
    protected $card;

    /**
     * 预付费
     * @return $this
     */
    protected function prePayment()
    {
        $packageInfo = $this->packageInfo;
        $prePayment = 0;
        if ($packageInfo['is_apart']) {
            $endOfMonth = Carbon::now()->endOfMonth()->day;
            $remainedDays = ($endOfMonth - Carbon::today()->day) + 1;

            $prePayment = ($this->unitPrice * $remainedDays) / $endOfMonth;
        }

        $this->prePaymentFee = round($prePayment);
        return $this;
    }

    /**
     * 卡欠费
     * @return $this
     */
    protected function overBill()
    {
        $this->overBillFee = $this->card->overdue_bill?:0;
        return $this;
    }

    /**
     * 原价折扣后价格
     * @return $this
     */
    protected function discountFee()
    {
        // 获取代理商的提价折扣
        if ($this->agent) {
            $upPrice = ceil($this->unitPrice * $this->agent->seal_discount / 100);
        } else {
            $upPrice = $this->unitPrice;
        }

        $discount = round($this->packageRepository->getDiscount($this->packageInfo->id, $this->agent->user_id) / 100, 4);
        $favourablePrice = $upPrice * $discount;
        $this->discountedFee = $favourablePrice;

        return $this;
    }

    /**
     * 平台提成费用
     * @return $this
     */
    protected function cutPrice()
    {
        $cut_price = ceil($this->unitPrice * $this->agent->discount / 100)
            + $this->overBillFee + $this->prePaymentFee; // 对应的代理商收回超用的钱

        $this->cutPriceFee = $cut_price;
        return $this;
    }

    /**
     * 检查各级代理商是否金额充足。
     * @return array
     * @throws OrderException
     */
    protected function checkAgentMoney()
    {
        $agent = $this->agent;
        $cut_price = $this->cutPriceFee;
        if (!$cut_price) {
            $cut_price = ceil($this->unitPrice * $agent->discount / 100);
        }
        $agentCutInfo = [];
        $agentCutInfo[] = [
            'id' => $agent->id,
            'price' => $cut_price,
            'discount' => $agent->discount
        ];

        if (!Money::where('agent_id', $agent->id)->first() ||
            Money::where('agent_id', $agent->id)->first()->balance < $cut_price) {
            throw new OrderException('代理商余额不足', 400950);
        }

        $secondAgent = $agent->parentAgent()->first();
        if ($secondAgent) { // 2级
            $secondCutPrice = ceil($this->unitPrice * $secondAgent->discount / 100);
            $secondDiscount = $secondAgent->discount;
            $agentCutInfo[] = [
                'id' => $secondAgent->id,
                'price' => $secondCutPrice,
                'discount' => $secondDiscount
            ];
            if (!Money::where('agent_id', $secondAgent->id)->first() ||
                Money::where('agent_id', $secondAgent->id)->first()->balance < $secondCutPrice) {
                throw new OrderException('代理商余额不足', 400950);
            }

            $firstAgent = $secondAgent->parentAgent()->first();
            if ($firstAgent) { // 1级
                $firstCutPrice = ceil($this->unitPrice * $firstAgent->discount / 100);
                $firstDiscount = $firstAgent->discount;
                $agentCutInfo[] = [
                    'id' => $firstAgent->id,
                    'price' => $firstCutPrice,
                    'discount' => $firstDiscount
                ];
                if (!Money::where('agent_id', $firstAgent->id)->first() ||
                    Money::where('agent_id', $firstAgent->id)->first()->balance < $firstCutPrice) {
                    throw new OrderException('代理商余额不足', 400950);
                }
            }
        }

        return $agentCutInfo;
    }

    /**
     * 设置总价
     * @return $this
     */
    public function calculateTotalFee()
    {
        $this->checkBase(); // 检查初始项

        $this->prePayment()->overBill()->discountFee()->cutPrice();
        $this->totalFee = $this->prePaymentFee + $this->discountedFee + $this->overBillFee;
        return $this;
    }

    /**
     * 生成支付订单
     * @param $userId
     * @param AgentInfo $agent
     * @param Card $card
     * @return array
     * @throws OrderException
     */
    public function order($userId, $agent = null, $card = null)
    {
        $this->card = $card;
        $this->agent = $agent;
        $this->checkBase(); // 检查初始项

        // 计算需要支付的金额
        $this->calculateTotalFee();

        // 检查各级代理商余额是否充足（最多3级）
        $agentCutInfo = $this->checkAgentMoney();

        $order = [
            'code' => $this->generateCode(),
            'user_id' => $userId,
            'address_id' => $this->address?:0,
            'card_id' => $this->card->id,
            'card_agent' => $this->card->agent->id,
            'money_agent' => $agent->id,
            'agent_name' => $agent->name,
            'agent_cut_info' => json_encode($agentCutInfo),
            'package_id' => $this->packageInfo->id,
            'cut_discount' => $agent->discount,
            'cut_price' => $this->cutPriceFee,
            'prepayment' => $this->prePaymentFee ? 1 : 0,    //是否续下个月的
            'prepayment_fee' => $this->prePaymentFee ?: 0,
            'overdue_bill' => $this->overBillFee,    //欠费钱
            'favourable_price' => $this->discountedFee,
            'total_fee' => $this->totalFee,
            'out_trade_no' => date('YmdHms'),
            'status' => 1,     //未支付状态
        ];

        if ($order = $this->orderRepository->create($order)) {     //等待发起微信支付
            return $order;
        } else {
            throw new OrderException('创建订单失败', 400951);
        }
    }

    public function checkBase()
    {
        if (!$this->packageInfo) {
            throw new \InvalidArgumentException('未提供正确的套餐参数！');
        }

        if (!$this->agent) {
            throw new \InvalidArgumentException('未提供正确的代理商参数！');
        }

        if (!$this->card) {
            throw new \InvalidArgumentException('未提供正确的卡参数！');
        }
    }

    /**
     * 设置代理商
     * @param $agent
     * @return $this
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * 设置卡
     * @param $card
     * @return $this
     */
    public function setCard($card)
    {
        $this->card = $card;
        return $this;
    }
}