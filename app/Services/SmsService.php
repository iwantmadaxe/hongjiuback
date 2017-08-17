<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Services;

use App\Repositories\SmsCaptchaRepository;
use App\Repositories\SmsLogRepository;
use Caikeal\LaravelSms\sms\Facades\Sms;

class SmsService
{
	const TYPE_VERIFY_PHONE = 1;

	public $config = [
	  self::TYPE_VERIFY_PHONE => [
	      'template_id' => 1, //模板
          'expiration' => 1, //分钟
      ],
    ];

    /**
     * @var SmsCaptchaRepository
     */
	public $smsCaptchaRepository;

    /**
     * @var SmsLogRepository
     */
	public $smsLogRepository;



	public function __construct(
	    SmsCaptchaRepository $smsCaptchaRepository,
        SmsLogRepository $smsLogRepository

    )
    {
        $this->smsCaptchaRepository = $smsCaptchaRepository;
        $this->smsLogRepository = $smsLogRepository;
    }

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
	 * 检查验证码是否有效 若有效则清除已使用的验证码
	 *
	 * @param $phone
	 * @param $code
	 * @param $type
	 * @return bool
	 */
	public function check($phone, $code, $type)
	{
	    $record = $this->smsCaptchaRepository->model->where(['phone' => $phone, 'code' => $code, 'type' => $type])->first();

		if ($record && $record->expiration >= time()) {
			$this->smsCaptchaRepository->destory($record->id);  //删除已使用的验证码
			return true;
		} elseif (! $record) {
		    return false;
		} elseif ($record->expiration < time()) {
			return false;
		}
	}

	/**
	 * 检查该号码该type是否刚刚发过验证码不需要再次发送
	 *
	 * @param $phone
	 * @param $type
     * @return  bool
	 */
	private function justSent($phone, $type)
	{
		$tplTime = date("Y-m-d H:i:s", time() - 100);
		$record = $this->smsCaptchaRepository->model->where(['phone' => $phone, 'type' => $type])
				->where('created_at', '>=', $tplTime)
				->first();
		if ($record) {
			return true;
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
		$record = $this->smsCaptchaRepository->model->where(['phone' => $phone, 'type' => $type])->first();
		if ($record && time() <= $record->expiration) {
			return $record->code;
		} else {
			return false;
		}
	}

	/**
	 * 发送验证码
	 * @param $phone
	 * @param $type
	 * @return array
	 */
	public function send($phone, $type)
	{
	    if($this->justSent($phone,$type)){
	        return ['error'=>1,'message'=>'验证码已发送'];
        }
        $code = $this->stillValid($phone,$type);
        $new_code = $code ? $code:$this->generateCode();
		$response = Sms::to($phone)
			->template('YunTongXun', $this->config[$type]['template_id'])
			->data([$new_code, $this->config[$type]['expiration']])
			->send();

		if ($response->statusCode == '000000' && ! $this->stillValid($phone, $type)) {
			$record = [
				'phone' => $phone,
				'code'  => $new_code,
				'type'  => $type,
				'expiration' => time() + $this->config[$type]['expiration'] * 60,
			];
			if ($this->smsCaptchaRepository->create($record)) {
				return ['error'=>0,'message'=>'发送成功'];
			} else {
				return ['error'=>1,'message'=>'验证码保存失败'];
			}
		} else {
			return ['error'=>1,'message'=>'发送失败','response_code'=>$response->statusCode];
		}
	}
}