<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Repositories;

use App\Models\Card;

class CardRepository extends BaseRepository
{
    private $agent;

	public $status = [
		1 => '在用',
		2 => '用户报停',
		3 => '用户拆机',
		4 => '欠停(双向)',
		5 => '欠停(单向)',
		6 => '违章停机',
		7 => '挂失',
		8 => '施工未完',
		9 => '活卡待激活',
		10 => '停机',
		11 => '预拆机停机',
		12 => '数据异常停机',
		13 => '局方原因停机',
		14 => '未知状态',
	];

	public $type;

	public function __construct(Card $card)
	{
		$this->model = $card;
		$this->type = config('service.card_type');
	}

    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
	}

	public function paginate($request)
	{
		$cards = $this->model;

		if ($this->agent) {
		    $cards = $cards->where('agent_id', $this->agent);
        }

		if ($request->has('id')) {
			$cards = $cards->where('id', $request['id']);
		}
		if ($request->has('agent_id')) {
			$cards = $cards->where('agent_id', $request['agent_id']);
		}
		if ($request->has('code')) {
			$cards = $cards->where('code', $request['code']);
		}
		if ($request->has('iccid')) {
			$cards = $cards->where('iccid', $request['iccid']);
		}
		if ($request->has('acc_number')) {
			$cards = $cards->where('acc_number', $request['acc_number']);
		}
		if ($request->has('type')) {
			$cards = $cards->where('type', $request['type']);
		}
		if ($request->has('status')) {
			$cards = $cards->where('status', $request['status']);
		}
		if ($request->has('telecom_account')) {
			$cards = $cards->where('telecom_id', $request['telecom_account']);
		}
		if ($request->has('flow_total') || $request->has('flow_last') || $request->has('flow_used')) {
            $cards = $cards->whereHas('flow', function ($query) use ($request) {
                if ($request->has('flow_total')) {
                    $query->whereBetween('total_flow', $request['flow_total']);
                }
                if ($request->has('flow_last')) {
                    $query->whereBetween('flow', $request['flow_last']);
                }
                if ($request->has('flow_used')) {
                    $query->whereBetween('flow', $request['used']);
                }
            });
        }
		//$cards->join();
		return $cards->paginate();
	}

	public function updateFobidden($cardId, $forbidden)
	{
		$card = $this->model->find($cardId);
		if ($card->update(['is_forbidden' => $forbidden])) {
			return true;
		}
	}

	public function updateStatus($cardId, $status)
	{
		$card = $this->model->find($cardId);
		if ($card->update(['status' => $status])) {
			return true;
		}
	}
}