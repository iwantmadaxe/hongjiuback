<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Http\Requests\DiscountCreateRequest;
use App\Http\Requests\DiscountsUpdateRequest;
use App\Http\Requests\DiscountUpdateRequest;
use App\Http\Requests\PackageCreateRequest;
use App\Models\Package;
use App\Models\PackageDiscount;
use App\Transformers\PackageDiscountListTransformer;
use App\Transformers\PackageListTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends BaseController
{
	private $package;
	private $packageDiscount;

	public function __construct(Package $package, PackageDiscount $packageDiscount)
	{
		$this->package = $package;
		$this->packageDiscount = $packageDiscount;
	}

	public function index(Request $request, PackageListTransformer $listTransformer)
	{
        $packageQuery = $this->package->query();
        $queryList = ['display_name', 'name', 'flow', 'is_apart', 'is_back', 'status', 'type'];
        $queries = $request->all();
        foreach ($queries as $k => $v) {
            if (in_array($k, $queryList) && $v !== null) {
                $packageQuery->where($k, $v);
            }
        }

        $packages = $packageQuery->paginate();
		return $this->response()->paginator($packages, $listTransformer);
	}

	public function getList()
	{
		$packages = $this->package->all()->pluck('name', 'id');
		return $this->response()->array(['data' => $packages]);
	}

	public function create(PackageCreateRequest $request)
	{
        // 创建的如果是购卡套餐，关闭老的的购卡套餐
        $type = $request->input('type');
        $status = $request->input('status');
        if ($type == 5 && $status == 1) {
            $buyCardPackage = $this->package->query()
                ->where('type', 5)
                ->where('status', 1)
                ->first();
            if ($buyCardPackage) {
                $buyCardPackage->update(['status' => 0]);
            }
        }

		$package = $request->all();
		$package['price'] *= 100;
		$this->package->create($package);
        return $this->response()->array(['message' => 'success']);
	}

	public function delete(Request $request)
	{
		foreach ($request['ids'] as $key => $value) {
			$this->package->find($value)->delete();
		}
		return $this->response()->array(['data' => ['message' => '删除成功']]);
	}

	public function enable(Request $request, $enable)
	{
		if (!in_array($enable, [0, 1])) {
			return $this->response()->array(['data' => ['message' => '参数错误']]);
		}

		// 现存的购卡套餐
        $buyCardPackage = $this->package->query()
            ->where('type', 5)
            ->where('status', 1)
            ->first();

		foreach ($request['ids'] as $key => $value) {
			$packageInfo = $this->package->find($value);
			// 只有一个购卡套餐可以存在
			if ($enable == 1 && $packageInfo->type == 5 &&
                $buyCardPackage && $buyCardPackage->status == 1) {
                $buyCardPackage->update(['status' => 0]);
            }
            $packageInfo->update(['status' => $enable]);
		}
		return $this->response()->array(['data' => ['message' => '操作成功']]);
	}

	public function discountList(Request $request, PackageDiscount $packageDiscount, PackageDiscountListTransformer $listTransformer)
	{
		$condition = [
			'agent_id', 'package_id', 'discount'
		];
		foreach ($condition as $key => $value) {
			if ($request->has($value)) {
				$packageDiscount = $packageDiscount->where($value, $request[$value]);
			}
		}
        $admin = Auth::guard('admin')->user();
        if (!$admin->isAdmin()) {
            $packageDiscount = $packageDiscount->where('agent_id', $admin->id);
        }
		$list = $packageDiscount->paginate();
		return $this->response()->paginator($list, $listTransformer);
	}

	public function createDiscount(DiscountCreateRequest $request)
	{
		$discount = $request->all();
		if ($this->packageDiscount->create($discount)) {
			return $this->response()->array(['data' => ['message' => '创建成功']]);
		}
	}

	public function showDiscount($id, PackageDiscountListTransformer $listTransformer)
	{
		$discount = $this->packageDiscount->find($id);
		return $this->response()->item($discount, $listTransformer);
	}

	public function updateDiscount(DiscountUpdateRequest $request, $id)
	{
		$discount = $this->packageDiscount->find($id);
		if ($discount->update(['discount' => $request['discount']])) {
			return $this->response()->array(['data' => ['message' => '修改成功']]);
		}
	}

	public function deleteDiscount($id)
	{
		$discount = $this->packageDiscount->find($id);
		if ($discount->delete()) {
			return $this->response()->array(['data' => ['message' => '删除成功']]);
		}
	}

	public function updateDiscounts(DiscountsUpdateRequest $request)
	{
		foreach ($request['ids'] as $key => $value) {
			$this->packageDiscount->find($value)->update(['discount' => $request['discount']]);
		}
		return $this->response()->array(['data' => ['message' => '修改成功']]);
	}
}