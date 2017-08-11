<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Services\TelecomApi\Core;

use App\Exceptions\TeleComException;
use App\Repositories\TelecomStatusRepository;
use EasyWeChat\Support\Collection;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractApi
 * @package App\Services\TelecomApi\Core
 */
class AbstractApi
{
    /**
     * @var DesUtils
     */
    protected $desUtils;

    protected $telecomStatusRepository;

    /**
     * @var \App\Services\TelecomApi\Core\Http
     */
    protected $http;

    /**
     * 请求参数
     * @var array
     */
    protected $params=[];

    /**
     * sign参数
     * @var array
     */
    protected $signParams=[];

    /**
     * AbstractApi constructor.
     * @param DesUtils $desUtils
     * @param TelecomStatusRepository $telecomStatusRepository
     */
    public function __construct(DesUtils $desUtils, TelecomStatusRepository $telecomStatusRepository)
    {
        $this->desUtils = $desUtils;
        $this->telecomStatusRepository = $telecomStatusRepository;
    }

    /**
     * 通过iccid发起请求
     * @param $card
     * @param $method
     * @param array ...$argument
     * @throws TeleComException
     */
    public function setParams($card, $method, $argument=[])
    {
        if (!$telecomAccount = $card->telecomAccount) {
            throw new TeleComException('电信卡的接口账号无效');
        }

        // 编辑sign必填
        $this->signParams['method'] = $method;
        $this->signParams['user_id'] = $telecomAccount->account;
        $this->signParams['password'] = $telecomAccount->password;
        $this->signParams['iccid'] = $card->iccid;
        foreach ($argument as $k => $v) {
            $this->signParams[$k] = $v;
            $this->params[$k] = $v;
        }

        // 请求项
        $this->params['method'] = $method;
        $this->params['user_id'] = $telecomAccount->account;
        $this->params['iccid'] = $card->iccid;
        $this->params['passWord'] = $this->encryptPassword($telecomAccount);
        $this->params['sign'] = $this->generateSign($telecomAccount);
    }

    /**
     * 通过acc_num发起请求
     * @param $card
     * @param $method
     * @param array $argument
     * @throws TeleComException
     */
    public function setParamsByAccNum($card, $method, $argument=[])
    {
        if (!$telecomAccount = $card->telecomAccount) {
            throw new TeleComException('电信卡的接口账号无效');
        }

        // 编辑sign必填
        $this->signParams['method'] = $method;
        $this->signParams['user_id'] = $telecomAccount->account;
        $this->signParams['password'] = $telecomAccount->password;
        $this->signParams['access_number'] = $card->acc_number;
        foreach ($argument as $k => $v) {
            $this->signParams[$k] = $v;
            $this->params[$k] = $v;
        }

        $this->params['method'] = $method;
        $this->params['user_id'] = $telecomAccount->account;
        $this->params['access_number'] = $card->acc_number;
        $this->params['passWord'] = $this->encryptPassword($telecomAccount);
        $this->params['sign'] = $this->generateSign($telecomAccount);
    }

    /**
     * 加密密码
     * @param $telecomAccount
     * @return string
     */
    protected function encryptPassword($telecomAccount)
    {
        $account = $telecomAccount;
        return $this->desUtils->strEnc($account->password, $account->key1, $account->key2, $account->key3);
    }

    /**
     * 加密生成sign值
     * @param $telecomAccount
     * @return string
     */
    protected function generateSign($telecomAccount)
    {
        $sign = $this->desUtils->naturalOrdering(array_values($this->signParams));
        return $this->desUtils->strEnc($sign, $telecomAccount->key1, $telecomAccount->key2, $telecomAccount->key3);
    }

    /**
     * @return Http
     */
    public function getHttp()
    {
        if (is_null($this->http)) {
            $this->http = new Http();
        }

        if (count($this->http->getMiddlewares()) === 0) {
            $this->registerHttpMiddlewares();
        }

        return $this->http;
    }

    /**
     * @param $method
     * @param array $args
     * @return Collection
     */
    public function parseJSON($method, array $args)
    {
        $http = $this->getHttp();

        $contents = $http->parseJSON(call_user_func_array([$http, $method], $args));

        return new Collection($contents);
    }

    /**
     * @param $method
     * @param array $args
     * @return Collection
     */
    public function parseXML($method, array $args)
    {
        $http = $this->getHttp();

        $contents = $http->parseXML(call_user_func_array([$http, $method], $args));

        $contents = $this->getContent($contents);

        return new Collection($contents);
    }

    /**
     * @param $content
     * @return mixed
     */
    private function getContent($content)
    {
        return $content;
        // return $content['web_NEW_DATA_TICKET_QRsp'];
    }

    protected function registerHttpMiddlewares()
    {
        // retry
        $this->http->addMiddleware($this->retryMiddleware());
    }

    protected function retryMiddleware()
    {
        return Middleware::retry(
            function ($retries, RequestInterface $request, ResponseInterface $response = null) {
                if ($response && $body = $response->getBody()) {
                    // Retry on server errors
                    if (stripos($body, 'RspCode>9000')) { // 电信挂了且尝试3次一致，停止尝试
                        // echo $retries;
                        if ($retries > 3) {
                            // 设置环境变量,电信是挂了,为1
                            $this->telecomStatusRepository->setDead();

                            return false;
                        }

                        return true;
                    } else {
                        $this->telecomStatusRepository->setDead(0);
                    }
                }

                return false;
            }
        );
    }
}