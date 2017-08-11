<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Repositories;

use App\Models\Package;

class PackageRepository extends BaseRepository
{
    public $status = [
        0 => '禁用',
        1 => '启用',
    ];

    public $type;

	public function __construct(Package $package)
	{
		$this->model = $package;
		$this->type = config('service.package_type');
	}

    /**
     * 获取所有可用的套餐
     * @return mixed
     */
	public function getUseList()
	{
		return Package::where('status', 1)->get();
	}

    /**
     * 获取对应类型的套餐
     * @param $cardType
     * @return mixed
     */
    public function getOwnPackageList($cardType)
    {
        return Package::where('type', $cardType)->where('status', 1)->get();
	}

    /**
     * 该套餐是否可用
     * @param $packageId
     * @return bool
     */
    public function canUse($packageId)
    {
        return !!Package::where('id', $packageId)->where('status', 1)->count();
	}

	private function isFixed($card)
	{
		return in_array($card->type, [2, 3, 4]);
	}

    /**
     * 获取折扣价, 返回百分比
     * @param $packageId
     * @param $agentId
     * @return int
     */
    public function getDiscount($packageId, $agentId)
    {
        $discountInfo = Package::where('id', $packageId)->first()
            ->discount()->where('agent_id', $agentId)->first();
        if ($discountInfo) {
            return $discountInfo->discount;
        } else {
            return 100;
        }
	}

    /**
     * 可兑换的套餐
     * @param int $cardType
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getPackageListForExchange($cardType=0)
    {
        $info = Package::query();
        if ($cardType) {
            $info->where('type', $cardType);
        }
        return $info->where('is_exchange', 1)->where('status', 1)->get();
	}

    /**
     * 获取对应的可兑换套餐
     * @param $packageId
     * @param int $cardType
     * @return mixed
     */
    public function getPackageForExchange($packageId, $cardType=0)
    {
        $info = Package::query();
        if ($cardType) {
            $info->where('type', $cardType);
        }
        return $info->where('id', $packageId)
            ->where('is_exchange', 1)
            ->where('status', 1)->first();
    }
}