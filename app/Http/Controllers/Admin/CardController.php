<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Exceptions\TeleComException;
use App\Http\Requests\CardCreateRequest;
use App\Http\Requests\CardImportAgentRequest;
use App\Http\Requests\CardImportRequest;
use App\Http\Requests\CardNetRequest;
use App\Http\Requests\CardOprateRequest;
use App\Jobs\ImportCardAgentToDB;
use App\Jobs\ImportCardToDB;
use App\Models\File;
use App\Repositories\CardRepository;
use App\Services\Excel;
use App\Services\TelecomCard\TelecomCardManager;
use App\Transformers\CardListTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class CardController extends BaseController
{
	private $cardRepository;

	public function __construct(CardRepository $cardRepository)
	{
		$this->cardRepository = $cardRepository;
	}

    /**
     * 查询卡
     * @param Request $request
     * @param CardListTransformer $listTransformer
     * @return mixed
     */
	public function getCards(Request $request, CardListTransformer $listTransformer)
	{
	    $admin = Auth::guard('admin')->user();
	    if ($admin->isAdmin()) {
            $cards = $this->cardRepository->paginate($request);
        } else {
	        $cards = $this->cardRepository->setAgent($admin->id)->paginate($request);
        }
		return $this->response()->paginator($cards, $listTransformer);
	}

    /**
     * 创建卡
     * @param CardCreateRequest $request
     * @return mixed
     */
	public function create(CardCreateRequest $request)
	{
		$card = [
			'code' => $request['code'],
			'iccid' => $request['iccid'],
			'acc_number' => $request['acc_number'],
			'type' => $request['type'],
			'status' => $request['status'],
			'telecom_id' => $request['telecom_id'],
			'created_time' => 	Carbon::parse($request['created_time'])->toDateTimeString(),
		];
		if ($this->cardRepository->create($card)) {
			return $this->response()->array(['data' => ['message' => '创建成功']]);
		} else {
			return $this->response()->error('创建失败', 400, 400345);
		}

	}

	public function import(CardImportRequest $request, Excel $excel)
	{
		//解析excel
		$file = 'storage/app/'.File::find($request['file_id'])->path;
		$cards = $excel->parse($file);

		$this->dispatch(new ImportCardToDB($cards, $request['type'], $request['status'], $request['telecom_id'], $request['created_time']));
		return $this->response()->array(['data' => ['message' => '数据导入中']]);
	}

	public function importAgent(CardImportAgentRequest $request, Excel $excel)
	{
		$file = 'storage/app/'.File::find($request['file_id'])->path;
		$cards = $excel->parse($file);

		$this->dispatch(new ImportCardAgentToDB($cards, $request['agent_id']));
		return $this->response()->array(['data' => ['message' => '数据导入中']]);
	}

    /**
     * 卡操作
     * @param CardOprateRequest $request
     * @param $operation
     * @param TelecomCardManager $cardManager
     * @return mixed
     */
	public function operate(CardOprateRequest $request, $operation, TelecomCardManager $cardManager)
	{
		$allApi = Config::get('service.telecom_api');
		if (!in_array($operation, $allApi)) {
		    return $this->response()->error('暂无此操作', 500, 50000);
        }

		$cards = $request['cards'];
        $errInfo['cards'] = []; // 操作失败数组
        $info = []; // 操作成功数组
		foreach ($cards as $key => $cardId) {
			$card = $this->cardRepository->find($cardId);

			if ($operation == 'enable' || $operation == 'disable') {
				$forbidden = ($operation == 'disable') ? 1 : 0;
				if (!$this->cardRepository->updateFobidden($cardId, $forbidden)) {
                    $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => '操作失败'];
				} else {
                    $info[] = ['card_id' => $cardId];
                }
			} else {
			    try {
                    if (!$cardManager->card($card)->$operation()) {
                        $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => '操作失败'];;
                    } else {
                        $info[] = ['card_id' => $cardId];
                    }
                } catch (\Exception $e) {
                    $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => $e->getMessage()];
                }
			}
		}
		return $this->response()->array(['data' => ['message' => '操作成功！', 'cards' => $info, 'error_cards' => $errInfo]]);
	}

    /**
     * 卡状态列表
     * @return mixed
     */
	public function status()
	{
		return $this->response()->array(['data' => $this->cardRepository->status]);
	}

    /**
     * 更新卡状态
     * @param CardOprateRequest $request
     * @param TelecomCardManager $telecomCardManager
     * @return mixed
     */
    public function updateStatus(CardOprateRequest $request, TelecomCardManager $telecomCardManager)
    {
        $cards = $request['cards'];
        $info = []; // 操作成功数组
        $errInfo['cards'] = []; // 操作失败数组
        foreach ($cards as $key => $cardId) {
            $card = $this->cardRepository->find($cardId);
            try {
                $status = $telecomCardManager->card($card)->updateCardStatus();
                $info[] = [
                    'card_id' => $card->id,
                    'status' => $status['status']
                ];
            } catch (\Exception $e) {
                $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => $e->getMessage()];
            }
        }

        return $this->response()->array(['data' => ['message' => '操作成功！', 'cards' => $info, 'error_cards' => $errInfo]]);
	}

    /**
     * 卡产品列表
     * @param CardOprateRequest $request
     * @param TelecomCardManager $telecomCardManager
     * @return mixed
     */
    public function productionInfo(CardOprateRequest $request, TelecomCardManager $telecomCardManager)
    {
        $cards = $request['cards'];
        $info = []; // 操作成功数组
        $errInfo['cards'] = []; // 操作失败数组
        foreach ($cards as $key => $cardId) {
            $card = $this->cardRepository->find($cardId);
            try {
                $result = $telecomCardManager->card($card)->cardProductInfo();
                $info[] = [
                    'card_id' => $card->id,
                    'result' => $result['result']
                ];
            } catch (\Exception $e) {
                $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => $e->getMessage()];
            }
        }

        return $this->response()->array(['data' => ['message' => '操作成功！', 'cards' => $info, 'error_cards' => $errInfo]]);
	}

    /**
     * 添加达量断网
     * @param CardNetRequest $request
     * @param TelecomCardManager $telecomCardManager
     * @return mixed
     */
    public function addNet(CardNetRequest $request, TelecomCardManager $telecomCardManager)
    {
        $cards = $request['cards'];
        $quota = $request['quota'];
        $type = $request['type'];

        $info = []; // 操作成功数组
        $errInfo['cards'] = []; // 操作失败数组
        foreach ($cards as $key => $cardId) {
            $card = $this->cardRepository->find($cardId);
            try {
                $telecomCardManager->card($card)->addNet($quota, $type);
                $info[] = [
                    'card_id' => $card->id
                ];
            } catch (\Exception $e) {
                $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => $e->getMessage()];
            }
        }

        return $this->response()->array(['data' => ['message' => '操作成功！', 'cards' => $info, 'error_cards' => $errInfo]]);
    }

    /**
     * 更新达量断网
     * @param CardNetRequest $request
     * @param TelecomCardManager $telecomCardManager
     * @return mixed
     */
    public function updateNet(CardNetRequest $request, TelecomCardManager $telecomCardManager)
    {
        $cards = $request['cards'];
        $quota = $request['quota'];
        $type = $request['type'];

        $info = []; // 操作成功数组
        $errInfo['cards'] = []; // 操作失败数组
        foreach ($cards as $key => $cardId) {
            $card = $this->cardRepository->find($cardId);
            try {
                $telecomCardManager->card($card)->updateNet($quota, $type);
                $info[] = [
                    'card_id' => $card->id
                ];
            } catch (\Exception $e) {
                $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => $e->getMessage()];
            }
        }

        return $this->response()->array(['data' => ['message' => '操作成功！', 'cards' => $info, 'error_cards' => $errInfo]]);
    }

    /**
     * 取消达量断网
     * @param CardNetRequest $request
     * @param TelecomCardManager $telecomCardManager
     * @return mixed
     */
    public function deleteNet(CardNetRequest $request, TelecomCardManager $telecomCardManager)
    {
        $cards = $request['cards'];
        $quota = $request['quota'];
        $type = $request['type'];

        $info = []; // 操作成功数组
        $errInfo['cards'] = []; // 操作失败数组
        foreach ($cards as $key => $cardId) {
            $card = $this->cardRepository->find($cardId);
            try {
                $telecomCardManager->card($card)->deleteNet($quota, $type);
                $info[] = [
                    'card_id' => $card->id
                ];
            } catch (\Exception $e) {
                $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => $e->getMessage()];
            }
        }

        return $this->response()->array(['data' => ['message' => '操作成功！', 'cards' => $info, 'error_cards' => $errInfo]]);
    }

    /**
     * 达量断网恢复上网
     * @param CardOprateRequest $request
     * @param TelecomCardManager $telecomCardManager
     * @return mixed
     */
    public function recoverNet(CardOprateRequest $request, TelecomCardManager $telecomCardManager)
    {
        $cards = $request['cards'];

        $info = []; // 操作成功数组
        $errInfo['cards'] = []; // 操作失败数组
        foreach ($cards as $key => $cardId) {
            $card = $this->cardRepository->find($cardId);
            try {
                $telecomCardManager->card($card)->recoverNet();
                $info[] = [
                    'card_id' => $card->id
                ];
            } catch (\Exception $e) {
                $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => $e->getMessage()];
            }
        }

        return $this->response()->array(['data' => ['message' => '操作成功！', 'cards' => $info, 'error_cards' => $errInfo]]);
    }

    /**
     * 卡流量详情
     * @param CardOprateRequest $request
     * @param TelecomCardManager $telecomCardManager
     * @return mixed
     */
    public function flowDetail(CardOprateRequest $request, TelecomCardManager $telecomCardManager)
    {
        $cards = $request['cards'];

        $info = []; // 操作成功数组
        $errInfo['cards'] = []; // 操作失败数组
        foreach ($cards as $key => $cardId) {
            $card = $this->cardRepository->find($cardId);
            try {
                $result = $telecomCardManager->card($card)->detailFlowList();
                $info[] = [
                    'card_id' => $card->id,
                    'result' => $result['result']
                ];
            } catch (\Exception $e) {
                $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => $e->getMessage()];
            }
        }

        return $this->response()->array(['data' => ['message' => '操作成功！', 'cards' => $info, 'error_cards' => $errInfo]]);
    }

    /**
     * 更新卡流量
     * @param CardOprateRequest $request
     * @param TelecomCardManager $telecomCardManager
     * @return mixed
     */
    public function updateFlow(CardOprateRequest $request, TelecomCardManager $telecomCardManager)
    {
        $cards = $request['cards'];

        $info = []; // 操作成功数组
        $errInfo['cards'] = []; // 操作失败数组
        foreach ($cards as $key => $cardId) {
            $card = $this->cardRepository->find($cardId);
            try {
                $result = $telecomCardManager->card($card)->flow();
                $info[] = [
                    'card_id' => $card->id,
                    'result' => $result
                ];
            } catch (\Exception $e) {
                $errInfo['cards'][] = ['card_id' => $cardId, 'msg' => $e->getMessage()];
            }
        }

        return $this->response()->array(['data' => ['message' => '操作成功！', 'cards' => $info, 'error_cards' => $errInfo]]);
    }
}