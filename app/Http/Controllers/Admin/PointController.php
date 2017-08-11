<?php

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Http\Requests\PointExchangeApplyRequest;
use App\Http\Requests\PointExchangeRateRequest;
use App\Models\PointMoneyExchange;
use App\Models\PointMoneyRecord;
use App\Models\PointPackageRecord;
use App\Models\PointRecord;
use App\Models\Recommend;
use App\Models\User;
use App\Transformers\PointMoneyRecordingListTransformer;
use App\Transformers\PointPackageRecordingListTransformer;
use App\Transformers\PointRecordAdminListTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointController extends BaseController
{
    /**
     * 后台积分列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $receiver = $request->input('receiver');
        $sponsor = $request->input('sponsor');

        $pointRecord = PointRecord::query()->with(['receiverUser', 'sponsorUser']);

        if ($receiver) {
            $pointRecord = $pointRecord->where('receiver', $receiver);
        }

        if ($sponsor) {
            $pointRecord = $pointRecord->where('sponsor', $sponsor);
        }
        $pointRecord = $pointRecord->paginate();

        return $this->response()->paginator($pointRecord, new PointRecordAdminListTransformer());
    }

    /**
     * 积分发起者列表
     * @return mixed
     */
    public function sponsor()
    {
        $sponsorIds = PointRecord::query()->pluck('sponsor')->unique();

        $sponsors = User::whereIn('id', $sponsorIds)->get(['id', 'name']);

        return $this->response()->array($sponsors);
    }

    /**
     * 积分收到者列表
     * @return mixed
     */
    public function receiver()
    {
        $receiverIds = PointRecord::query()->pluck('receiver')->unique();

        $receivers = User::whereIn('id', $receiverIds)->get(['id', 'name']);

        return $this->response()->array($receivers);
    }

    /**
     * 积分兑换现金申请列表
     * @param Request $request
     * @return mixed
     */
    public function exchangeApplyList(Request $request)
    {
        // todo 权限分类
        $info = $request->only(['status', 'card_no', 'card_bank', 'card_owner', 'card_owner_phone']);
        $sql = PointMoneyRecord::with('user');
        foreach ($info as $k => $v) {
            if ($v) {
                $sql->where($k, $v);
            }
        }
        $records = $sql->paginate();

        return $this->response()->paginator($records, new PointMoneyRecordingListTransformer());
    }

    /**
     * 积分兑现申请处理
     * @param $id
     * @param PointExchangeApplyRequest $request
     * @return mixed
     */
    public function exchangeApply($id, PointExchangeApplyRequest $request)
    {
        $status = $request->input('status');
        $record = PointMoneyRecord::where('id', $id)->first();
        if ($status == 2) {
            // 同意，添加积分扣除记录
            $divInfo = PointRecord::create([
                'sponsor' => $record->user_id,
                'receiver' => $record->user_id,
                'point' => -1 * $record->consume_points,
                'type' => 3,
                'pointable_id' => $record->id,
                'pointable_type' => '\App\Models\PointMoneyRecord'
            ]);
            $record->status = 2;
            $record->update();
        } elseif ($status == 3) {
            // 拒绝，返回积分
            $record->status = 3;
            $record->update();
            Recommend::where('user_id', $record->user_id)->increment('points', $record->consume_points);
        }

        return $this->response()->array(['message' => 'success']);
    }

    /**
     * 兑换比率描述
     * @return mixed
     */
    public function pointExchangeRateList()
    {
        $info = PointMoneyExchange::get()->last();
        return $this->response()->array($info);
    }

    /**
     * 兑换比率修改
     * @param PointExchangeRateRequest $request
     * @return mixed
     */
    public function pointExchangeRate(PointExchangeRateRequest $request)
    {
        $points = $request->input('points');
        $description = $request->input('des');
        $info = PointMoneyExchange::get()->last();
        $info->points = $points;
        $info->description = $description;
        $info->update();
        return $this->response()->array(['message' => 'success']);
    }

    /**
     * 积分兑换套餐列表
     * @return mixed
     */
    public function pointPackageList()
    {
        $info = PointPackageRecord::with(['user', 'package', 'card'])
            ->orderBy('created_at', 'desc')
            ->paginate();

        return $this->response()->paginator($info, new PointPackageRecordingListTransformer());
    }
}
