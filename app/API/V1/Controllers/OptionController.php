<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Controllers;

use App\API\V1\BaseController;
use App\Repositories\AreaRepository;
use App\Transformers\AreaTransformer;

class OptionController extends BaseController
{
	public function area(AreaRepository $areaRepository, $code, AreaTransformer $areaTransformer)
	{
		if ($areaList = $areaRepository->ListBelongTo($code)) {
			return $this->response()->collection($areaList, $areaTransformer);
		} else {
			return $this->response()->error('无效的地区编号');
		}
	}
}