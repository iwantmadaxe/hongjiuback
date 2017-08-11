<?php

namespace App\Jobs;

use App\Repositories\CardRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportCardAgentToDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $cards;
	private $agent_id;


    public function __construct($cards, $agent_id)
    {
        $this->cards = $cards;
		$this->agent_id = $agent_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CardRepository $cardRepository)
    {
		$this->cards->each(function ($card) use ($cardRepository){
			$cardRecord = $cardRepository->model->where('code', $card->code)->first();
			$cardRecord->agent_id = $this->agent_id;
			$cardRecord->save();
		});

		return true;
    }
}
