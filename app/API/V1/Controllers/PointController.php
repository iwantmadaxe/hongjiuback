<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/26
 * Time: 下午3:07
 */

namespace App\API\V1\Controllers;


use App\API\V1\BaseController;
use App\API\V1\Requests\DrawingMoneyRequest;
use App\API\V1\Requests\DrawMoneyRequest;
use App\API\V1\Requests\PointPackageApplyRequest;
use App\Jobs\ExchangePackage;
use App\Models\Card;
use App\Models\PointMoneyExchange;
use App\Models\PointMoneyRecord;
use App\Models\PointPackageRecord;
use App\Models\PointRecord;
use App\Models\Recommend;
use App\Repositories\PackageRepository;
use App\Transformers\PointMoneyRecordingTransformer;
use App\Transformers\PointRecordListTransformer;
use App\Transformers\PointToPackageListTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointController extends BaseController
{
    /**
     * 积分明细记录
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $userId = $request['auth_user']->id;
        $points = PointRecord::where('receiver', $userId)->orderBy('created_at', 'desc')->paginate();

        return $this->response()->paginator($points, new PointRecordListTransformer());
    }

    /**
     * 当前的积分提现状况
     * @param Request $request
     * @return mixed
     */
    public function money(Request $request)
    {
        $userId = $request['auth_user']->id;
        $recording = PointMoneyRecord::where('user_id', $userId)->where('status', 1)->first();

        return $this->response()->item($recording, new PointMoneyRecordingTransformer());
    }

    /**
     * 积分提现换算金额
     * @param DrawingMoneyRequest $request
     * @return mixed
     */
    public function drawMoney(DrawingMoneyRequest $request)
    {
        $userId = $request['auth_user']->id;
        $points = $request->input('points');
        $userRecommend = Recommend::where('user_id', $userId)->first();

        if (!$userRecommend) {
            return $this->response()->errorBadRequest('您暂无积分可提现！');
        }

        if ($userRecommend->points < $points) {
            return $this->response()->errorBadRequest('您可提现的积分不足！');
        }

        $rate = PointMoneyExchange::get()->last()->points;

        $drawingMoney = floor($points / $rate);
        $usedPoints = intval($drawingMoney * $rate);

        return $this->response()->array(['data' => ['money' => $drawingMoney, 'used_points' => $usedPoints]]);
    }

    /**
     * 积分提现申请
     * @param DrawMoneyRequest $request
     * @return mixed
     */
    public function applyMoney(DrawMoneyRequest $request)
    {
        $userId = $request['auth_user']->id;
        $points = $request->input('points');
        $info['user_id'] = $userId;

        $userRecommend = Recommend::where('user_id', $userId)->first();
        if (!$userRecommend) {
            return $this->response()->errorBadRequest('您暂无积分可提现！');
        }

        if ($userRecommend->points < $points) {
            return $this->response()->errorBadRequest('您可提现的积分不足！');
        }

        // 计算具体提现金额
        $rate = PointMoneyExchange::get()->last()->points;
        $drawingMoney = floor($points / $rate);
        $info['exchange_money'] = $drawingMoney;
        $usedPoints = intval($drawingMoney * $rate);
        $info['consume_points'] = $usedPoints;

        $info['card_no'] = $request->input('card_no');
        $info['card_bank'] = $request->input('card_bank');
        $info['card_owner'] = $request->input('card_owner');
        $info['card_owner_phone'] = $request->input('card_owner_phone');

        // 预扣积分策略
        DB::beginTransaction();
        try {
            $userRecommend->decrement('points', $usedPoints);
            PointMoneyRecord::create($info);
            DB::commit();
            return $this->response()->array(['message' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response()->errorBadRequest('保存失败！');
        }
    }

    /**
     * 积分兑换规则
     * @return mixed
     */
    public function drawMoneyRule()
    {
        $info = PointMoneyExchange::get()->last();
        return $this->response()->array($info);
    }

    /**
     * 获取可兑换套餐
     * @param PackageRepository $packageRepository
     * @return mixed
     */
    public function pointPackages(PackageRepository $packageRepository)
    {
        // 暂时只能是叠加包，因为还未有其他套餐叠加的需求
        $packageInfo = $packageRepository->getPackageListForExchange(6);

        return $this->response()->collection($packageInfo, new PointToPackageListTransformer());
    }

    /**
     * 申请兑换套餐
     * @param PointPackageApplyRequest $request
     * @param PackageRepository $packageRepository
     * @return mixed
     */
    public function pointPackageApply(PointPackageApplyRequest $request, PackageRepository $packageRepository)
    {
        $packageId = $request->input('package_id');
        $cardId = $request->input('card_id');
        $user = $request['auth_user'];

        // 检查卡的有效性
        $cardInfo = Card::where('id', $cardId)->where('user_id', $user->id)->first();
        if ($cardInfo->is_forbidden == 1 || $cardInfo->status != 1) {
            return $this->response()->errorBadRequest('该卡暂未启用！');
        }

        // 由于需求，只提供叠加包
        $info = $packageRepository->getPackageForExchange($packageId, 6);

        // 判断套餐是否存在
        if (!$info) {
            return $this->response()->errorBadRequest('该套餐非兑换套餐！');
        }

        // 判断积分是否够开通
        $recommend = $user->recommendation()->first();
        if ($recommend && $info->points > $recommend->points) {
            return $this->response()->errorBadRequest('积分不足，不能兑换！');
        }

        // 添加兑换记录
        $pointPackageRecord = PointPackageRecord::create([
            'user_id' => $user->id,
            'package_id' => $packageId,
            'card_id' => $cardId,
            'consume_points' => $info->points,
            'status' => 1
        ]);

        // 扣除积分
        $recommend->points -= $info->points;
        $recommend->update();

        // 调用电信卡接口/卡/套餐
        try {
            $this->dispatch(new ExchangePackage($pointPackageRecord));
        } catch (\Exception $e) {
            logger($e);
        }

        // 提示套餐开通有延迟
        return $this->response()->array(['message' => '积分兑换的套餐开通会有些许延迟！']);
    }
}