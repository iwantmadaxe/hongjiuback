<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Repositories;

use App\Models\Area;

class AreaRepository extends BaseRepository
{
	private $area;

	public function __construct(Area $area)
	{
		$this->area = $area;
	}

	public function ListBelongTo($code)
	{
		$area = $this->area->where('belongs', $code)->get();

		return $area;
	}

	public function pathByCode($code)
	{
		if (is_null($code)) {
			return null;
		}
		$district = $this->area->where('code', $code)->first()->toArray();
		$city     = $this->area->where('code', $district['belongs'])->first()->toArray();
		$province = $this->area->where('code', $city['belongs'])->first()->toArray();

		foreach ($path = compact('province', 'city', 'district') as $key => $value) {
			unset($path[$key]['belongs'], $path[$key]['id']);
		}
		return $path;
	}
}