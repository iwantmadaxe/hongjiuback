<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/25
 * Time: 上午11:44
 */

namespace App\Services\TelecomOrder;


use App\Exceptions\OrderException;
use App\Models\Package;
use App\Repositories\OrderRepository;
use App\Repositories\PackageRepository;

abstract class OrderContract
{
    protected $orderRepository;
    protected $packageRepository;

    /**
     * 订单总价
     * @var int
     */
    protected $totalFee = 0;

    /**
     * 订单数量
     * @var int
     */
    protected $amount = 1;

    /**
     * 套餐单价
     * @var int
     */
    protected $unitPrice = 0;

    /**
     * 套餐信息
     * @var Package
     */
    protected $packageInfo = null;

    /**
     * 地址信息
     * @var null
     */
    protected $address = null;

    public function __construct(OrderRepository $orderRepository, PackageRepository $packageRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->packageRepository = $packageRepository;
    }

    /**
     * 生成唯一订单号
     * @return number
     */
    protected function generateCode()
    {
        $num = $this->orderRepository->model->where('code', 'like', date('Ymd') . '%')->count();
        $orderCode = date('Ymd') * 1000000 + $num + 1;

        return $orderCode;
    }

    /**
     * 设置数量
     * @param $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * 设置套餐单价和id
     * @param $packageId
     * @return $this
     * @throws OrderException
     */
    public function setPackage($packageId)
    {
        if (!$this->packageRepository->canUse($packageId)) {
            throw new OrderException('该套餐已经下架!', 400941);
        }
        $packageInfo = $this->packageRepository->model->find($packageId);

        $this->unitPrice = $packageInfo->price;
        $this->packageInfo = $packageInfo;
        return $this;
    }

    /**
     * 寄货地址
     * @param $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * 设置总价
     * @return mixed
     */
    abstract public function calculateTotalFee();

    /**
     * 生成支付订单
     * @param $userId
     * @param null $agent
     * @param null $card
     * @return array
     * @throws OrderException
     */
    abstract public function order($userId, $agent=null, $card=null);

    /**
     * 检查创建订单的基本信息
     * @return mixed
     */
    abstract public function checkBase();

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }
}