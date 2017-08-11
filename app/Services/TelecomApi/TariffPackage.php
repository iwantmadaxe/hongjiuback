<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Services\TelecomApi;

use App\Exceptions\TeleComException;
use App\Services\TelecomApi\Core\AbstractApi;

class TariffPackage extends AbstractApi
{
	const API_PACKAGE = 'http://api.ct10649.com:9001/m2m_ec/query.do';
	const API_OPERATE = 'http://api.ct10649.com:9001/m2m_ec/app/serviceAccept.do';

	const METHOD = [
		'package'   => 'queryPakage',
		'order'     => 'orderFlow',
		'unsubscribe' => 'unsubScribe',
	];

    /**
     * 获取套餐情况
     * @param $card
     * @param null $monthDate
     * @return array
     * @throws TeleComException
     */
	public function package($card, $monthDate = null)
	{
		$this->setParams($card, self::METHOD['package']);

		if (! is_null($monthDate)) {
			$this->params['monthDate'] = $monthDate;
		}

        $content = $this->parseXML('post', [self::API_PACKAGE, $this->params]);

        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } elseif ($content['web_CurrAcuRsp'] && isset($content['web_CurrAcuRsp']['CumulRspList'])) {
            $cumulationTotal = collect($content['web_CurrAcuRsp']['CumulRspList'])->sum('CUMULATION_TOTAL');    //可用的总流量
            $startDate = collect($content['web_CurrAcuRsp']['CumulRspList'])->pluck('START_TIME')->sort()->first();    //套餐流量计算的起始日期
            return [
                'code' => '0',
                'msg' => 'success',
                'cumulationTotal' => $cumulationTotal, // 单位：KB
                'startDate' => $startDate,
                'origin' => $content
            ];
        } else {
            throw new TeleComException('该卡上暂时没有套餐', 400301);
        }
	}

    /**
     * 订阅套餐
     * @param $card
     * @param $flowValue
     * @return array
     * @throws TeleComException
     */
	public function order($card, $flowValue)
	{
		$this->setParamsByAccNum($card, self::METHOD['order'], ['flowValue' => $flowValue]);

        $content = $this->parseJSON('post', [self::API_OPERATE, $this->params]);

        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } elseif ($content['info'] == 'success') {
            return [
                'code' => '0',
                'msg' => 'success',
                'origin' => $content
            ];
        } else {
            throw new TeleComException($content['Checkout'], 400311);
        }
	}

    /**
     * 退订套餐
     * @param $card
     * @param $flowValue
     * @return array
     * @throws TeleComException
     */
	public function unsubscribe($card, $flowValue)
	{
		$this->setParamsByAccNum($card, self::METHOD['unsubscribe'], ['flowValue' => $flowValue]);

        $content = $this->parseJSON('post', [self::API_OPERATE, $this->params]);

        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } elseif ($content['info'] == 'success') {
            return [
                'code' => '0',
                'msg' => 'success',
                'origin' => $content
            ];
        } else {
            throw new TeleComException($content['Checkout'], 400311);
        }
	}
}