<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Http\Requests\CertificateUpdateRequest;
use App\Models\Card;
use App\Models\Certificate;
use App\Transformers\CertificationListTransformer;
use App\Transformers\CertificationTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificationController extends BaseController
{
	private $certificate;

	public function __construct(Certificate $certificate)
	{
		$this->certificate = $certificate;
	}

	public function index(Request $request, CertificationListTransformer $certificationListTransformer)
	{
		$certificates = $this->certificate;
		$condition = [
			'id', 'status', 'code', 'id_number', 'username', 'phone', 'created_at'
		];
		$admin = Auth::guard('admin')->user();
		foreach ($condition as $key => $value) {
			if ($request->has($value)) {
				if ($value == 'created_at') {
					$certificates = $certificates->where('created_at', 'like', Carbon::parse($request['created_at'])->toDateString().'%');
				} else {
					$certificates = $certificates->where($condition, $request[$condition]);
				}
			}
		}
		// 根据用户权限显示认证数据
		if (!$admin->isAdmin()) {
            $certificates = $certificates->whereHas('card', function ($query) use ($admin) {
                $query->where('agent_id', $admin->id);
            });
        }
		$certificates = $certificates->paginate();
		return $this->response()->paginator($certificates, $certificationListTransformer);
	}

	public function show($id, CertificationTransformer $certificationTransformer)
	{
		$certificate = $this->certificate->find($id);
		return $this->response()->item($certificate, $certificationTransformer);
	}

	public function update($id, CertificateUpdateRequest $request)
	{
		$certificate = $this->certificate->find($id);
		if (!$certificate) {
            return $this->response()->errorBadRequest('认证信息不存在！');
        }

        // 对于已认证(2)和已拒绝(3)的卡不予在同一记录中重复处理
        if ($certificate['status'] == 2 || $certificate['status'] == 3) {
            return $this->response()->errorBadRequest('认证信息已经处理，不要重复处理！');
        }

        DB::beginTransaction();
		try {
            $certificate->update([
                'status' => $request['status'],
                'reason' => $request['reason'],
            ]);

            Card::where('code', $certificate['code'])->first()->update(['user_id' => $certificate['user_id']]);
            DB::commit();
        } catch (\Exception $e) {
		    DB::rollBack();
		    return $this->response()->errorBadRequest('保存失败！');
        }

        return $this->response()->array(['data' => ['message' => '成功']]);
	}
}