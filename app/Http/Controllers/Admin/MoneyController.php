<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Models\Money;
use App\Models\MoneyRecord;
use App\Transformers\MoneyManageTransformer;
use App\Transformers\MoneyRecordTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoneyController extends BaseController
{
	private $moneyRecord;

	public function __construct(MoneyRecord $moneyRecord)
	{
		$this->moneyRecord = $moneyRecord;
	}

	public function index(MoneyManageTransformer $manageTransformer, Request $request)
	{
		if ($request->has('agent_id')) {
			$money = Money::where('agent_id', $request['agent_id'])->paginate();
		} else {
			$money = Money::paginate();
		}
		return $this->response()->paginator($money, $manageTransformer);
	}

	public function add($agent_id, Request $request)
	{
		$money = Money::where('agent_id', $agent_id)->first();
		// 转换成分
		$moneyPay = round($request->input('money') * 100);
		if (!$money) {
			Money::create([
				'agent_id' => $agent_id,
				'balance' => $moneyPay,
				'all_money' => $moneyPay
			]);
		} else {
			$money->increment('balance', $moneyPay);
			$money->increment('all_money', $moneyPay);
		}
//        \Log::info(\Auth::guard('admin')->user());
		MoneyRecord::create([
			'type' => 2,
			'money' => $moneyPay,
			'agent_id' => $agent_id,
			'operator_id' => Auth::guard('admin')->user()->id,
		]);

		return $this->response()->array(['data' => ['message' => '添加成功']]);
	}

	public function record(MoneyRecordTransformer $moneyRecordTransformer, Request $request)
	{
		$condition = [
			'operator_id', 'type', 'order_id', 'money', 'agent_id',
		];
		$record = $this->moneyRecord->with(['agent', 'fromAgent']);
		foreach ($condition as $key => $value) {
			if ($request->has($value)) {
				$record = $record->where($value, $request[$value]);
			}
		}
        // 根据用户权限显示认证数据
        $admin = Auth::guard('admin')->user();
        if (!$admin->isAdmin()) {
            $record = $record->where('agent_id', $admin->id);
        }
		$record = $record->paginate();
		return $this->response()->paginator($record, $moneyRecordTransformer);
	}
}