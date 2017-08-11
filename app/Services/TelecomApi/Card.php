<?php
/*
 * Sometime too hot the eye of heaven shines
 */
namespace App\Services\TelecomApi;

use App\Exceptions\TeleComException;
use App\Services\TelecomApi\Core\AbstractApi;

class Card extends AbstractApi
{
	const API_CARD = 'http://api.ct10649.com:9001/m2m_ec/query.do';

	const METHOD = [
		'balance'   => 'queryBalance',    //余额
		'telephone' => 'getTelephone',    //获取手机号码
		'disable'   => 'disabledNumber',  //停机保号
		'active' => 'requestServActive',   //激活卡
		'status' => 'queryCardStatus',    //查看卡状态
        'recoverNet' => 'recoverNetAction', //达量断网恢复上网接口
        'setNet' => 'offNetAction', //设置达量断网
        'productInfo' => 'prodInstQuery', //卡产品资料
	];

	public function balance($card)
	{
		$this->setParamsByAccNum($card, self::METHOD['balance']);
		return $this->parseXML('get', [self::API_CARD, $this->params]);
	}

	public function telephone($user)
	{
		$this->setParams($user, self::METHOD['telephone']);
		return $this->parseXML('get', [self::API_CARD, $this->params]);
	}

    /**
     * 停机保号接口，停机功能
     * @param $card
     * @return array
     * @throws TeleComException
     */
	public function disable($card)
	{
		$this->setParamsByAccNum($card, self::METHOD['disable'], ['orderTypeId' => 19, 'acctCd' => '']);
        $content = $this->parseXML('post', [self::API_CARD, $this->params]);
        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } elseif ($content->result == 0 && $content->RspType == 0) {
            return [
                'code' => '0',
                'msg' => 'success',
                'origin' => $content
            ];
        } else {
            throw new TeleComException($content->resultMsg, 400211);
        }
	}

    /**
     * 停机保号接口，复机功能
     * @param $card
     * @return array
     * @throws TeleComException
     */
	public function enable($card)
	{
		$this->setParamsByAccNum($card, self::METHOD['disable'], ['orderTypeId' => 20, 'acctCd' => '']);
		$content = $this->parseXML('post', [self::API_CARD, $this->params]);
        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } elseif ($content->result == 0 && $content->RspType == 0) {
            return [
                'code' => '0',
                'msg' => 'success',
                'origin' => $content
            ];
        } else {
            throw new TeleComException($content->resultMsg, 400212);
        }
	}

    /**
     * 激活卡接口
     * @param $card
     * @return array
     * @throws TeleComException
     */
	public function active($card)
	{
		$this->setParamsByAccNum($card, self::METHOD['active']);
		$content = $this->parseXML('get', [self::API_CARD, $this->params]);
		// 激活卡接口错误处理
        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } elseif ($content->RESULT == 0) {
            return [
                'code' => '0',
                'msg' => 'success',
                'origin' => $content
            ];
        } else {
		    throw new TeleComException($content->SMSG, 400201);
        }
	}

    /**
     * 查询卡的状态。
     * @param $card
     * @return array
     * @throws TeleComException
     */
	public function status($card)
	{
		$this->setParamsByAccNum($card, self::METHOD['status']);
		$content = $this->parseXML('get', [self::API_CARD, $this->params]);
        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } else {
            $simpleContent = $content['Query_response'];
        }
        // 查询卡状态接口错误处理
        if ($simpleContent['BasicInfo']['result'] == 0) {
            return [
                'code' => '0',
                'msg' => 'success',
                'status' => $simpleContent['prodRecords']['prodRecord']['productInfo']['productStatusCd'],
                'origin' => $content
            ];
        } else {
            throw new TeleComException($simpleContent['BasicInfo']['resultMsg'], 400221);
        }
	}

    /**
     * 添加达量断网。
     * @param $card
     * @param $quota // 单位M, -1:无限制, 0:有上网流量产生就会立即断网
     * @param $type // 1:表示用户总使用量, 2:表示超出套餐外使用量
     * @return array
     * @throws TeleComException
     */
    public function addNet($card, $quota, $type)
    {
        $this->setParamsByAccNum($card, self::METHOD['setNet'], ['action' => 1, 'quota' => $quota, 'type' => $type]);
        $content = $this->parseXML('post', [self::API_CARD, $this->params]);
        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } else {
            $simpleContent = $content['SvcCont']['Response'];
        }

        if ($simpleContent['RspType'] == 0 && $simpleContent['RspCode'] == '0000') {
            return [
                'code' => '0',
                'msg' => 'success',
                'origin' => $content
            ];
        } else {
            throw new TeleComException($simpleContent['RspDesc'], 400311);
        }
	}

    /**
     * 更新达量断网。
     * @param $card
     * @param $quota // 单位M, -1:无限制, 0:有上网流量产生就会立即断网
     * @param $type // 1:表示用户总使用量, 2:表示超出套餐外使用量
     * @return array
     * @throws TeleComException
     */
    public function updateNet($card, $quota, $type)
    {
        $this->setParamsByAccNum($card, self::METHOD['setNet'], ['action' => 2, 'quota' => $quota, 'type' => $type]);
        $content = $this->parseXML('post', [self::API_CARD, $this->params]);
        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } else {
            $simpleContent = $content['SvcCont']['Response'];
        }

        if ($simpleContent['RspType'] == 0 && $simpleContent['RspCode'] == '0000') {
            return [
                'code' => '0',
                'msg' => 'success',
                'origin' => $content
            ];
        } else {
            throw new TeleComException($simpleContent['RspDesc'], 400321);
        }
	}

    /**
     * 取消达量断网功能（提示：达量断网下，应该先恢复再取消）
     * @param $card
     * @param $quota // 单位M, -1:无限制, 0:有上网流量产生就会立即断网
     * @param $type // 1:表示用户总使用量, 2:表示超出套餐外使用量
     * @return array
     * @throws TeleComException
     */
    public function deleteNet($card, $quota, $type)
    {
        $this->setParamsByAccNum($card, self::METHOD['setNet'], ['action' => 3, 'quota' => $quota, 'type' => $type]);
        $content = $this->parseXML('post', [self::API_CARD, $this->params]);
        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } else {
            $simpleContent = $content['SvcCont']['Response'];
        }

        if ($simpleContent['RspType'] == 0 && $simpleContent['RspCode'] == '0000') {
            return [
                'code' => '0',
                'msg' => 'success',
                'origin' => $content
            ];
        } else {
            throw new TeleComException($simpleContent['RspDesc'], 400331);
        }
    }

    /**
     * 达量断网恢复上网。
     * @param $card
     * @return array
     * @throws TeleComException
     */
    public function recoverNet($card)
    {
        $this->setParamsByAccNum($card, self::METHOD['recoverNet']);
        $content = $this->parseXML('post', [self::API_CARD, $this->params]);
        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } else {
            $simpleContent = $content['SvcCont']['Response'];
        }

        if ($simpleContent['RspType'] == 0 && $simpleContent['RspCode'] == '0000') {
            return [
                'code' => '0',
                'msg' => 'success',
                'origin' => $content
            ];
        } else {
            throw new TeleComException($simpleContent['RspDesc'], 400331);
        }
    }

    /**
     * 产品资料
     * @param $card
     * @return array
     * @throws TeleComException
     */
    public function productInfo($card)
    {
        $this->setParamsByAccNum($card, self::METHOD['productInfo']);
        $content = $this->parseXML('get', [self::API_CARD, $this->params]);
        if (!$content) {
            throw new TeleComException('No Content!', 400100);
        } else {
            $simpleContent = $content;
        }

        if ($simpleContent['resultCode'] == 0) {
            return [
                'code' => '0',
                'msg' => 'success',
                'result' => $simpleContent['result']['prodInfos'],
                'origin' => $content
            ];
        } else {
            throw new TeleComException($simpleContent['resultMsg'], 400411);
        }
    }
}