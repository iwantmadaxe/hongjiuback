<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/8/3
 * Time: 上午10:42
 */

namespace App\API\V1\Controllers;


use App\API\V1\BaseController;
use App\Models\Card;
use App\Services\TelecomApi\TariffPackage;
use App\Services\TelecomCard\TelecomCardManager;

class ApiTestController extends BaseController
{
    public function index(TelecomCardManager $telecomCardManager, TariffPackage $tariffPackage)
    {
        $card = Card::find(17);
//        $info = $tariffPackage->unsubscribe($card, 2011);
        $info = $telecomCardManager->card($card)->detailFlowList();
        return $info;
    }
}