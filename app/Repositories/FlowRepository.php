<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Repositories;

use App\Models\Flow;
use Carbon\Carbon;

class FlowRepository extends BaseRepository
{
	public function __construct(Flow $flow)
	{
		$this->model = $flow;
	}

	public function needFreshData($cardId)
	{
		$expiration = 10 * 60;    //10min内不再刷新
		if (!$this->model->where('card_id', $cardId)->first()) return true;

		$updatedTime = $this->model->where('card_id', $cardId)->first()->last_time;
		if (!$updatedTime) return true;

		return (time() - Carbon::parse($updatedTime)->timestamp) > $expiration;
	}

	public function updateFlow(array $flow, $cardId)
	{
        $flow['last_time'] = Carbon::now()->toDateTimeString();
		return $this->model->query()->updateOrCreate(['card_id' => $cardId], $flow);
	}
}