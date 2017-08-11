<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Http\Requests\OrderRunPackageApiRequest;
use App\Http\Requests\PercentageRequest;
use App\Models\Order;
use App\Repositories\AdminRepository;
use App\Transformers\OrderListTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends BaseController
{
	private $order;

	public function __construct(Order $order)
	{
		$this->order = $order;
	}

    /**
     * 订单列表
     * @param OrderListTransformer $listTransformer
     * @param Request $request
     * @return mixed
     */
	public function index(OrderListTransformer $listTransformer, Request $request)
	{
		$condition = [
			'id', 'money_agent', 'agent_id', 'user_id', 'package_name', 'code', 'status', 'is_back', 'card_code', 'package_id'
		];
		$orders = $this->order->with(['orderPackage', 'address']);
		foreach ($condition as $key => $value) {
			if ($value != 'card_code' && $value != 'package_id' && $request->has($value)) {
				$orders = $orders->where($value, $request[$value]);
			}
			if ($request->has('card_code')) {
			    $cardCode = $request->input('card_code');
                $orders = $orders->whereHas('card', function ($query) use ($cardCode) {
                    $query->where('code', $cardCode);
                });
            }
            if ($request->has('package_id')) {
                $packageId = $request->input('package_id');
                $orders = $orders->whereHas('orderPackage', function ($query) use ($packageId) {
                    $query->where('package_id', $packageId);
                });
            }
		}
        // 根据用户权限显示认证数据
        $admin = Auth::guard('admin')->user();
        if (!$admin->isAdmin()) {
            $orders = $orders->whereHas('receiveAgent.adminAccount', function ($query) use ($admin) {
                $query->where('id', $admin->id);
            });
        }
		$orders = $orders->orderBy('created_at', 'desc')->paginate();
		return $this->response()->paginator($orders, $listTransformer);
	}

	public function back(Request $request)
	{
		foreach ($request['ids'] as $key => $id) {
			//调用退订接口 消息队列
		}
		return $this->response()->array(['data' => ['message' => '退订中']]);
	}

	public function percentage(PercentageRequest $request, AdminRepository $adminRepository)
	{
		$rebate = $request->input('rebate', 10);
		$time = $request->input('time', Carbon::now()->firstOfMonth()->toDateTimeString().','.Carbon::now()->toDateTimeString());
        $agentId = $request->input('agent_id');

		// 根据权限查询，管理员全查，代理商只能查询他自己和下属
        $admin = Auth::guard('admin')->user();
        $agents = $adminRepository->getAgentList($admin);
        $agentsId = array_keys($agents);

        // 获取对应代理商
        $searchOrder = $this->order->query();
        if (!$agentId) {
            $searchOrder->whereIn('money_agent', $agentsId);
        } else {
            $searchOrder->where('money_agent', $agentId);
        }
		$orders = $searchOrder->whereBetween('pay_at', explode(',', $time))
            ->where('status', 2)->get();

        $groupedFee = $orders->groupBy('money_agent')
            ->map(function ($agentOrder) use ($rebate) {
                $total = $agentOrder->sum('total_fee');
                $cut = $agentOrder->sum('cut_price');
                return [
                    'total_fee' => round($total / 100, 2),
                    'cut_price' => round($cut / 100, 2),
                    'rebate' => round($total * $rebate / 10000, 2),
                    'profile' => round(($total - $cut) / 100, 2)
                ];
            })
            ->map(function ($fee, $agent) use ($agents) {
                return [
                    'agent_id' => $agent,
                    'agent_name' => $agents[$agent],
                    'data' => $fee
                ];
            })->values();
		return $this->response()->array([
			'data' => $groupedFee
		]);
	}

    /**
     * 订单订购套餐接口轮训状态更新（0，1之间更新）
     * @param OrderRunPackageApiRequest $request
     * @return mixed
     */
    public function setPackageType(OrderRunPackageApiRequest $request)
    {
        $orders = $request['orders'];

        $info = Order::whereIn('id', $orders)->update(['package_type' => $request->input('type')]);

        if ($info) {
            return $this->response()->array(['data' => ['message' => '操作成功！']]);
        }

        return $this->response()->errorBadRequest('保存失败！');
	}
}