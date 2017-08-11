<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Services;

use App\Exceptions\SmsException;
use App\Models\SmsRecord;
use App\Models\User;
use Caikeal\LaravelSms\sms\Facades\Sms;

class SmsService
{
	private $config = [
		'register' => [
			'template_id' => 1,
			'type_id' => 1,
			'expiration' => 3,    //有效数据,单位 分钟s
		],
		'login' => [
			'template_id' => 1,
			'type_id' => 2,
			'expiration' => 3,    //有效数据,单位 分钟s
		],
		'password' => [
			'template_id' => 1,
			'type_id' => 3,
			'expiration' => 3,    //有效数据,单位 分钟s
		],
		'updatePhone' => [
			'template_id' => 1,
			'type_id' => 4,
			'expiration' => 3,    //有效数据,单位 分钟s
		],
        'bindPhone' => [
            'template_id' => 1,
            'type_id' => 5,
            'expiration' => 3,    //有效数据,单位 分钟s
        ],
        'validateCard' => [
            'template_id' => 1,
            'type_id' => 6,
            'expiration' => 3,    //有效数据,单位 分钟s
        ]
	];

	/**
	 * 随机生成6位验证码
	 *
	 * @return string
	 */
	public function generateCode()
	{
		$code = '';
		for($i = 0; $i <6; $i++) {
			$code .= mt_rand(1, 9);
		}
		return $code;
	}

	/**
	 * 检查电话号码是否已经注册
	 *
	 * @param $phone
	 * @return mixed
	 */
	public function checkRegister($phone)
	{
		return User::where('phone', $phone)->first();
	}

	/**
	 * 检查验证码是否有效 若有效则清除已使用的验证码
	 *
	 * @param $phone
	 * @param $code
	 * @param $type
	 * @return bool
	 * @throws SmsException
	 */
	public function check($phone, $code, $type)
	{
		$record = SmsRecord::where(['phone' => $phone, 'code' => $code, 'type' => $this->config[$type]['type_id']])->first();

		if ($record && $record->expiration >= time()) {
			SmsRecord::destroy($record->id);    //删除已使用的验证码
			return true;
		} elseif (! $record) {
			throw new SmsException('无效的验证码', 400102);
		} elseif ($record->expiration < time()) {
			throw new SmsException('验证码已过期', 400101);
		}
	}

	/**
	 * 检查该号码该type是否刚刚发过验证码不需要再次发送
	 *
	 * @param $phone
	 * @param $type
	 * @throws SmsException
	 */
	private function justSent($phone, $type)
	{
		$tplTime = date("Y-m-d H:i:s", time() - 100);
		$record = SmsRecord::where(['phone' => $phone, 'type' => $this->config[$type]['type_id']])
				->where('created_at', '>=', $tplTime)
				->first();
		if ($record) {
			throw new SmsException('验证码已发送', 400103);
		}
	}

	/**
	 * 检查验证码是否在有效期内
	 *
	 * @param $phone
	 * @param $type
	 * @return bool
	 */
	private function stillValid($phone, $type)
	{
		$record = SmsRecord::where(['phone' => $phone, 'type' => $this->config[$type]['type_id']])->first();
		if ($record
			&& time() <= $record->expiration) {
			return $record;
		} else {
			return false;
		}
	}

	/**
	 * 发送验证码
	 *
	 * @param $phone
	 * @param $type
	 * @return bool
	 * @throws SmsException
	 */
	public function send($phone, $type)
	{
		$this->justSent($phone, $type);
		if ($record = $this->stillValid($phone, $type)) {
			$code = $record->code;
		} else {
			$code = $this->generateCode();
		}
		$response = Sms::to($phone)
			->template('YunTongXun', $this->config[$type]['template_id'])
			->data([$code, $this->config[$type]['expiration']])
			->send();

		if ($response->statusCode == '000000' && ! $this->stillValid($phone, $type)) {
			$record = [
				'phone' => $phone,
				'code'  => $code,
				'type'  => $this->config[$type]['type_id'],
				'expiration' => time() + $this->config[$type]['expiration'] * 60,
			];
			if (SmsRecord::create($record)) {
				return true;
			} else {
				throw new SmsException('验证码保存失败', 400105);
			}
		} else {
			throw new SmsException($response->statusMsg, 400106);
		}
	}
}