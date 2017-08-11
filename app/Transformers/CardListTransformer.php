<?php

namespace App\Transformers;

use App\Repositories\CardRepository;
use League\Fractal\TransformerAbstract;


/**
 * Class CardListTransformer
 * @package App\Transformers
 */
class CardListTransformer extends TransformerAbstract
{
	private $cardRepository;

	public function __construct(CardRepository $cardRepository)
	{
		$this->cardRepository = $cardRepository;
	}

	/**
     * @param $data
     * @return array
     */
    public function transform($data)
    {
        return [
        	'id' => $data->id,
			'code' => $data->code,
			'iccid' => $data->iccid,
			'acc_number' => $data->acc_number,
			'telecom_account' => $data->telecomAccount->name,
			'status' => $this->status($data->status),
			'is_forbidden' => $data->is_forbidden ? 1 : 0,
			'type' => $this->type($data->type),
			'agent_name' => ($data->agent) ? ($data->agent->agent ? $data->agent->agent->name : '未指定') : '未指定',
			'parent_agent' => ($data->agent) ? ($data->agent->agent ? ($data->agent->agent->parentAgent ? $data->agent->agent->parentAgent->name : '') : '') : '',
			'grandparent_agent' => ($data->agent) ? ($data->agent->agent ? ($data->agent->agent->parentAgent ? ($data->agent->agent->parentAgent->parentAgent ? $data->agent->agent->parentAgent->parentAgent->name : '') : '') : '') : '',
			'flow_total' => ($data->flow) ? $data->flow->total_flow : 0,
			'flow_used' => ($data->flow) ? $data->flow->used : 0,
			'flow_last' => ($data->flow) ? $data->flow->flow : 0,
            'certificated' => !!($this->certificated($data)),
			'updated_at' => ($data->flow) ? $data->flow->last_time : $data->updated_at->toDateTimeString(),
		];
    }

    /**
     * @param $status
     * @return mixed
     */
	private function status($status)
	{
		$map = $this->cardRepository->status;
		return $map[$status];
	}

    /**
     * @param $type
     * @return mixed
     */
    private function type($type)
	{
        $map = $this->cardRepository->type;
		return $map[$type];
	}

    public function certificated($data)
    {
        $tpl = $data->certification()->orderBy('created_at', 'asc')->get()->last();
        return $tpl ? $tpl->status == 2 : 0;
	}
}