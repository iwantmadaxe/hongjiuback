<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Console\Schedules;

use App\Models\Card;
use App\Models\Flow;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RegularFlowSchedule extends FlowSchedule
{

	protected $level = FlowSchedule::LEVEL_REGULAR;

	public function handler(Collection $cards)
	{
		foreach ($cards as $key => $card) {
		    if (!$card) {
		        continue;
            }
		    try {
                $manage = $this->cardManager->card(Card::find($card->id));
                if ($manage->serverDead()) {
                    continue;
                }
                $flow = $manage->flow();
                // 检查是否需要停机
                $manage->checkForStop($flow['remained']);
            } catch (\Exception $e) {
                logger($e);
            }
		}

	}
}