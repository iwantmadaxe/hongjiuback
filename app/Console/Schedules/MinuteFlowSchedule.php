<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Console\Schedules;

use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MinuteFlowSchedule extends FlowSchedule
{
	protected $level = FlowSchedule::LEVEL_MINUTE;

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