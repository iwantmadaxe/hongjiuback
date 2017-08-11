<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Controllers;

use App\API\V1\BaseController;
use App\API\V1\Requests\CardNickNameRequest;
use App\Jobs\ImportCardToDB;
use App\Models\Card;
use App\Services\Excel;
use Illuminate\Http\Request;

class CardController extends BaseController
{
	public function import(Excel $excel, Request $request)
	{
		//保存excel

		//导入
		$file = base_path('app/Services/test.xlsx');
		$cards = $excel->parse($file);
		$this->dispatch(new ImportCardToDB($cards));
		return $this->response()->array(['data' => ['message' => '文件已上传, 正在导入']]);
	}

    /**
     * 修改卡昵称
     * @param $id
     * @param CardNickNameRequest $request
     * @return mixed
     */
    public function changeName($id, CardNickNameRequest $request)
    {
        $cardInfo = Card::where('id', $id)->first();
        $nickName = $request->input('nick_name');

        if (!$cardInfo) {
            return $this->response()->errorBadRequest('该卡不存在！');
        }

        $cardInfo->other_name = $nickName;
        $cardInfo->update();

        return $this->response()->array(['message' => '保存成功！']);
	}
}