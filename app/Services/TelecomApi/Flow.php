<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Services\TelecomApi;

use App\Exceptions\TeleComException;
use App\Services\TelecomApi\Core\AbstractApi;

/**
 * Class Flow
 *
 * @package App\Services\TelecomApi
 */

class Flow extends AbstractApi
{
	const API_FLOW = 'http://api.ct10649.com:9001/m2m_ec/query.do';

	const METHOD = [
		'day'   => 'queryTrafficOfToday',
		'month' => 'queryTraffic',
		'date'  => 'queryTrafficByDate',
	];

	public function today($card)
	{
		$this->setParams($card, self::METHOD['day']);

		return $this->parseXML('get', [self::API_FLOW, $this->params]);
	}

    /**
     * 查询当月流量详单
     * @param $card
     * @param int $needDtl
     * @return array
     * @throws TeleComException
     */
	public function month($card, $needDtl = 0)
	{
		$this->setParams($card, self::METHOD['month']);
        $this->params['needDtl']   = $needDtl;

        $content = $this->parseXML('get', [self::API_FLOW, $this->params]);

        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } elseif ($content['web_NEW_DATA_TICKET_QRsp']['IRESULT'] == 0) {
            return [
                'code' => '0',
                'msg' => 'success',
                'result' => $content,
                'origin' => $content
            ];
        } else {
            throw new TeleComException('调取明细错误', 400401);
        }
	}

    /**
     * 月时间段流量查询
     * @param $card
     * @param $startDate
     * @param $endDate
     * @param int $needDtl
     * @return array
     * @throws TeleComException
     */
	public function date($card, $startDate, $endDate, $needDtl = 0)
	{
		$this->setParams($card, self::METHOD['date']);

		$this->params['startDate'] = $startDate;
		$this->params['endDate']   = $endDate;
		$this->params['needDtl']   = $needDtl;

		$content = $this->parseXML('get', [self::API_FLOW, $this->params]);

        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } elseif ($content['web_NEW_DATA_TICKET_QRsp']['IRESULT'] == 0) {
            $flow = $content['web_NEW_DATA_TICKET_QRsp']['TOTAL_BYTES_CNT'];    // 单位为M
            $flow = str_replace('MB', '', $flow);
            return [
                'code' => '0',
                'msg' => 'success',
                'flow' => $flow,
                'origin' => $content
            ];
        } else {
            throw new TeleComException('调取明细错误', 400401);
        }
	}
}