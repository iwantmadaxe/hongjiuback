<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Api\V1\Controllers;

use App\API\V1\Requests\CardBuyOrderRequest;
use App\Models\Card;
use App\Models\DeliveryAddress;
use App\Models\Recommend;
use App\Models\User;
use App\Models\Package;
use App\Models\AgentInfo;
use App\Repositories\TelecomStatusRepository;
use App\Services\TelecomOrder\BuyCardOrder;
use App\Services\TelecomOrder\PackageCardOrder;
use Illuminate\Http\Request;
use EasyWeChat\Payment\Order;
use App\API\V1\BaseController;
use App\Repositories\FlowRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Cookie;
use EasyWeChat\Foundation\Application;
use App\Repositories\PackageRepository;
use App\Transformers\PackageListTransformer;
use App\Services\TelecomCard\TelecomCardManager;
use App\Transformers\PurchaseHistoryTransformer;


class PackageController extends BaseController
{
	private $orderRepository;
	private $flowRepository;
	private $packageRepository;

	public function __construct(OrderRepository $orderRepository, FlowRepository $flowRepository, PackageRepository $packageRepository)
	{
		$this->flowRepository  = $flowRepository;
		$this->orderRepository = $orderRepository;
		$this->packageRepository = $packageRepository;
	}

    /**
     * 套餐下单
     * @param $packageId
     * @param $cardId
     * @param Request $request
     * @param PackageCardOrder $packageCardOrder
     * @return array|string
     */
	public function order($packageId, $cardId, Request $request, PackageCardOrder $packageCardOrder)
	{
		if (!Cookie::get('openid')) {
			return $this->response()->error('下单失败:没有openid', 400, 400801);
		}

		$userId = $request['auth_user']->id;
		$card = Card::find($cardId);

		if (!$card->agent) {
			return $this->response()->error('卡尚未指定代理商', 400, 400802);
		}

        // 拿到当前公众号属于的代理商
        $agent_id = Cookie::get('wechat');
        if (!$agent_id) {
            return $this->response()->error('下单失败:没有代理商', 400, 400801);
        }

        $agent = AgentInfo::where('id', $agent_id)->first();
        if (!$agent) {
            return $this->response()->error('下单失败:没有代理商', 400, 400801);
        }

        $order = $packageCardOrder->setPackage($packageId)->order($userId, $agent, $card);

		$options = [
			// 前面的appid什么的也得保留哦
			'app_id' => $agent->app_id,
			'app_secret' => $agent->app_secret,
			// payment
			'payment' => [
				'merchant_id'        => $agent->merchant,
				'key'                => $agent->key,
				'notify_url'         => '默认的订单回调地址',       // 你也可以在下单时单独设置来想覆盖它
			],
		];
		$app = new Application($options);
		$payment = $app->payment;

		$packageName = Package::where('id', $packageId)->first()->display_name;

		$attributes = [
			'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
			'body'             => $packageName,
			'detail'           => $packageName,
			'out_trade_no'     => $order['out_trade_no'],
			'total_fee'        => $order['total_fee'], // 单位：分
			'notify_url'       => 'http://telecom.odinsoft.com.cn/api/v1/notify_url/'.$agent_id, // 支付结果通知网址，如果不设置则会使用配置里的默认地址
			'openid'           => Cookie::get('openid'), // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
			// ...
		];
		$order = new Order($attributes);
		$result = $payment->prepare($order);

		if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
			$prepayId = $result->prepay_id;
			$config = $payment->configForJSSDKPayment($prepayId); // 返回数组
			return $config;
		} else {
			return $this->response()->error('下单失败', 400, 400801);
		}

	}

	public function record(Request $request, PurchaseHistoryTransformer $transformer)
	{
		$userId = $request['auth_user']->id;
		$orders = $this->orderRepository->getRecordByUser($userId);

		return $this->response()->collection($orders, $transformer);
	}

    /**
     * 套餐列表
     * @param Request $request
     * @param PackageCardOrder $packageCardOrder
     * @return mixed
     */
	public function packageList(Request $request, PackageCardOrder $packageCardOrder)
	{
	    $code = $request->input('code');
	    $cardInfo = Card::where('id', $code)->first();
        $userId = $request['auth_user']->id;

        if (!$cardInfo) {
            return $this->response()->errorBadRequest('未查询到对应的卡！', 400700);
        }

        if (!$cardInfo->agent) {
            return $this->response()->error('卡尚未指定代理商', 400, 400802);
        }

        // 拿到当前公众号属于的代理商
        $agent_id = Cookie::get('wechat');

        if (!$agent_id) {
            return $this->response()->errorBadRequest('请在微信中登录！', 400770);
        }

        $agent = AgentInfo::where('id', $agent_id)->first();
        if (!$agent) {
            return $this->response()->error('您支付的代理商已被禁用！', 400, 400802);
        }
        $upDiscount = $agent->seal_discount ?:100;


		$packages = $this->packageRepository->getOwnPackageList($cardInfo->type)
            ->map(function ($item) use ($upDiscount, $packageCardOrder, $agent, $cardInfo){
                $fee = $packageCardOrder->setPackage($item->id)
                    ->setAgent($agent)->setCard($cardInfo)
                    ->calculateTotalFee()->totalFee;
                $item['price'] = $fee;
                return $item;
            });

		return $this->response()->collection($packages, new PackageListTransformer());
	}

	/**
	 * 流量查询
	 *
	 * @param Request $request
	 * @param User $user
	 * @param TelecomCardManager $cardManager
	 * @param TelecomStatusRepository $telecomStatusRepository
	 * @return mixed
	 */
	public function balance(Request $request, User $user, TelecomCardManager $cardManager, TelecomStatusRepository $telecomStatusRepository)
	{
		$userId = $request['auth_user']->id;
		$card = Card::where('id', $request['card_id'])->where('user_id', $userId)->first();

		// 判断该卡是否属于该用户
		if (!$card) {
		    return $this->response()->errorBadRequest('您未持有该卡！');
        }

		$manage = $cardManager->card($card);
        $response = $manage->currentFlow();

		$response['card_number'] = $card->code;
		$res = [];
		collect($response)->each(function ($val, $k) use (&$res) {
            $res[snake_case($k)] = $val;
        });
		return $this->response()->array(['data' => $res]);
	}

    /**
     * 购卡的套餐
     * @return mixed
     */
    public function buyCardList()
    {
        $buyCard = Package::where('type', 5)->where('status', 1)->first();

        return $this->response()->item($buyCard, new PackageListTransformer());
	}

    /**
     * 购卡下单
     * @param $packageId
     * @param CardBuyOrderRequest $request
     * @param BuyCardOrder $buyCardOrder
     * @return array|string
     */
    public function buyCardOrder($packageId, CardBuyOrderRequest $request, BuyCardOrder $buyCardOrder)
    {
        if (!Cookie::get('openid')) {
            return $this->response()->error('下单失败:没有openid', 400, 400801);
        }

        $address = $request->input('address');
        $amount = $request->input('amount', 0);
        $recommend = $request->input('recommend'); // 推荐人hash值
        $userId = $request['auth_user']->id;

        // 检查地址
        $isExist = DeliveryAddress::where('user_id', $userId)->where('id', $address)->count();
        if (!$isExist) {
            return $this->response()->error('下单失败:未提供寄送地址', 400, 400821);
        }

        // 检查推荐人
        $recommendInfo = Recommend::where('uuid', $recommend)->first();
        $recommendId = '';
        if ($recommendInfo) {
            $recommendId = $recommendInfo->user_id;
        }

        // 拿到当前公众号属于的代理商
        $agent_id = Cookie::get('wechat');
        if (!$agent_id) {
            return $this->response()->error('下单失败:没有代理商', 400, 400801);
        }

        $agent = AgentInfo::where('id', $agent_id)->first();
        if (!$agent) {
            return $this->response()->error('下单失败:没有代理商', 400, 400801);
        }

        $order = $buyCardOrder->setRecommender($recommendId)->setPackage($packageId)
            ->setAmount($amount)->setAddress($address)->order($userId, $agent);

        $options = [
            // 前面的appid什么的也得保留哦
            'app_id' => $agent->app_id,
            'app_secret' => $agent->app_secret,
            // payment
            'payment' => [
                'merchant_id'        => $agent->merchant,
                'key'                => $agent->key,
                'notify_url'         => '默认的订单回调地址',       // 你也可以在下单时单独设置来想覆盖它
            ],
        ];
        $app = new Application($options);
        $payment = $app->payment;

        $packageName = Package::where('id', $packageId)->first()->display_name;

        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => $packageName,
            'detail'           => $packageName,
            'out_trade_no'     => $order['out_trade_no'],
            'total_fee'        => $order['total_fee'], // 单位：分
            'notify_url'       => 'http://telecom.odinsoft.com.cn/api/v1/notify_url/'.$agent_id, // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => Cookie::get('openid'), // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);

        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
            $config = $payment->configForJSSDKPayment($prepayId); // 返回数组
            return $config;
        } else {
            return $this->response()->error('下单失败', 400, 400801);
        }
	}
}