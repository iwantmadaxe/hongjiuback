<?php
/*
 * Sometime too hot the eye of heaven shines
 */
namespace App\Api\V1\Controllers;

use App\API\V1\Requests\SendSmsRequest;
use App\API\V1\BaseController;
use App\Exceptions\SmsException;
use App\Services\SmsService;

class SmsController extends BaseController
{
	private $smsService;

	public function __construct(SmsService $smsService)
	{
		$this->smsService = $smsService;
	}

	public function send(SendSmsRequest $request)
	{
		$phone = $request['phone'];
		$type  = $request['type'];

		if (($type == 'register' || $type == 'bindPhone') && $this->smsService->checkRegister($phone)) {
			throw new SmsException('该手机号码已注册', 400100);
		}
		$this->smsService->send($phone, $type);
		return $this->response()->array(['data' => ['message' => '发送成功']]);
	}
}