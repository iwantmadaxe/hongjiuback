<?php

namespace App\Jobs;

use App\Repositories\CardRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportCardToDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cards;

	private $type;
	private $status;
	private $telecom_id;
	private $created_time;

    public function __construct($cards, $type, $status, $telecom_id, $created_time)
    {
        $this->cards = $cards;

		$this->type = $type;
		$this->status = $status;
		$this->telecom_id = $telecom_id;
		$this->created_time = $created_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CardRepository $cardRepository)
    {
        $this->cards->each(function ($card) use ($cardRepository){
            $isExist = $cardRepository->model->where('iccid', $card->iccid)
                ->orWhere('code', $card->code)->orWhere('acc_number', $card->acc_number)->count();
            if (!$isExist) {
                $data = [
                    'type' => (int)$this->type,
                    'status' => (int)$this->status,
                    'created_time' => Carbon::parse($this->created_time)->toDateTimeString(),
                    'code' => (int)$card->code,
                    'iccid' => (int)$card->iccid,
                    'acc_number' => (int)$card->acc_number,
                    'telecom_id' => (int)$this->telecom_id,
                ];
                $cardRepository->create($data);
            }
		});

		return true;
    }
}
