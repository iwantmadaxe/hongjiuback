<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Api\V1\Controllers;

use App\API\V1\BaseController;
use App\API\V1\Requests\QrCodeRequest;
use App\API\V1\Requests\CardCertificateRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdatePhoneRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Card;
use App\Models\Certificate;
use App\Models\Recommend;
use App\Repositories\UserRepository;
use App\Services\SmsService;
use App\Transformers\CertificatesTransformer;
use App\Transformers\MessageTransformer;
use App\Transformers\RecommendQrCodeTransformer;
use App\Transformers\UserCardTransformer;
use App\Transformers\UserProfileTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends BaseController
{
	private $smsService;
	private $userRepository;

	public function __construct(UserRepository $userRepository, SmsService $smsService)
	{
		$this->smsService = $smsService;
		$this->userRepository = $userRepository;
	}

	public function profile(Request $request, UserProfileTransformer $transformer)
	{
		$user = $request['auth_user'];
		return $this->response()->item($user, $transformer);
	}

	public function update(UserUpdateRequest $request)
	{
		$userInfo = $request->only([
			'name',
			'email',
			'areaCode',
			'address',
		]);
		if ($this->userRepository->updateInfo($userInfo, $request['auth_user']->id)) {
			return $this->response()->array(['data' => ['message' => '用户信息更新成功']]);
		}
	}

    /**
     * 更新手机号
     * @param UserUpdatePhoneRequest $request
     * @return mixed
     */
	public function updatePhone(UserUpdatePhoneRequest $request)
	{
		//验证短信验证码是否有效
		$this->smsService->check($request['auth_user']->phone, $request['smsCode'], 'updatePhone');

		//更新手机号码
		if ($this->userRepository->updatePhone($request['phone'], $request['auth_user']->id)) {
			return $this->response()->array(['data' => ['message' => '手机号更新成功']]);
		}
	}

    /**
     * 绑定手机号
     * @param UserUpdatePhoneRequest $request
     * @return mixed
     */
    public function bindPhone(UserUpdatePhoneRequest $request)
    {
        //验证短信验证码是否有效
        $this->smsService->check($request['phone'], $request['smsCode'], 'bindPhone');

        //更新手机号码
        if ($this->userRepository->updatePhone($request['phone'], $request['auth_user']->id)) {
            return $this->response()->array(['data' => ['message' => '手机号更新成功']]);
        }
	}

	public function updatePassword(UserUpdatePasswordRequest $request)
	{
		//todo 验证旧密码是否匹配
		//更新密码
		$newPassword = $request['newPassword'];
		if ($this->userRepository->updatePassword($newPassword, $request['auth_user']->id)) {
			return $this->response()->array(['data' => ['message' => '密码更新成功']]);
		}
	}

	public function messages(Request $request, MessageTransformer $messageTransformer)
	{
		$userId = $request['auth_user']->id;
		$user = $this->userRepository->find($userId);

		return $this->response()->collection($user->unreadNotifications, $messageTransformer);
	}

	public function readMessage(Request $request)
	{
		$userId = $request['auth_user']->id;
		$user = $this->userRepository->find($userId);

		$user->unreadNotifications->markAsRead();
		return $this->response()->array(['data' => ['message' => '已读']]);
	}

	public function certificate(CardCertificateRequest $request)
	{
		//卡是不是属于这个公众号的代理商的 ？？不知道需不需要
        // 卡是否存在
		if (!Card::where('code', $request['card_code'])->first()) {
			return $this->response()->errorBadRequest('无效的卡号');
		}

		// 在认证中(1)/已认证(2)的卡不能提交审核
        $certificated = Certificate::where('code', $request['card_code'])->whereIn('status', [1,2])->first();
		if ($certificated) {
            if ($certificated['status'] == 1) {
                return $this->response()->errorBadRequest('该卡正在认证中，可以联系您的服务商加快处理！');
            } else if ($certificated['status'] == 2) {
                return $this->response()->errorBadRequest('该卡已经认证，无需再次认证！');
            }
        }

        // 每人限制认证5张卡(已认证(2)+待认证卡(1))
        $countCard = Certificate::where('user_id', $request['auth_user']->id)->whereIn('status', [1,2])->count();
		if ($countCard >= 5) {
            return $this->response()->errorBadRequest('您已达到拥有卡的上限！');
        }

        // 验证验证码
        $this->smsService->check($request['phone'], $request['smsCode'], 'validateCard');    //验证码检查

        //保存身份证照片
		//$front_image = $request->file('front_image')->getClientOriginalName();
		$front_image = $request->file('front_image')->store('public/certificate');
		$back_image = $request->file('back_image')->store('public/certificate');

		$card_id = Card::where('code', $request['card_code'])->first()->id;

		Certificate::create([
			'front_image' => str_replace('public/', '', $front_image),
			'back_image' => str_replace('public/', '', $back_image),
			'user_id' => $request['auth_user']->id,
			'card_id' => $card_id,
			'code' => $request['card_code'],
			'id_number' => $request['id_number'],
			'username' => $request['name'],
			'phone' => $request['phone'],
			'status' => 1,   //未审核
		]);

		return $this->response()->array(['data' => ['message' => '提交成功']]);
	}

	public function certificates(Request $request, CertificatesTransformer $certificatesTransformer)
	{
		$certificates = Certificate::where('user_id', $request['auth_user']->id)->get();
		return $this->response()->collection($certificates, $certificatesTransformer);
	}

    /**
     * 卡列表
     * @param Request $request
     * @return mixed
     */
	public function cards(Request $request)
	{
		$userId = $request['auth_user']->id;
		$cards = $this->userRepository->find($userId)->cards;
		if ($cards) {
			return $this->response()->collection($cards, new UserCardTransformer());
		} else {
			return $this->response()->array(['data' => '']);
		}
	}

    /**
     * 生成二维码
     * @param QrCodeRequest $request
     * @return mixed
     */
    public function generateQrCode(QrCodeRequest $request)
    {
        $userId = $request['auth_user']->id;
        $recommend = Recommend::where('user_id', $userId)->first();

        if ($recommend && $recommend->url) {
            return $this->response()->item($recommend, new RecommendQrCodeTransformer());
        }

        $uuid = sha1($userId);
        // 生成推荐网址
        $baseUrl = $request->input('qrurl');
        $urlString = collect(explode('/', $baseUrl))->filter(function ($val) {
            return str_contains($val, 'home') || str_contains($val, 'wechat');
        })->implode('/');

        if (!$urlString) {
            return $this->response()->errorBadRequest('网址为空！');
        }

        $url = url($urlString).'/#/buy-post?recommend='.$uuid;

        $qrpic = QrCode::format('png')->size(350)->margin(1)->errorCorrection('H')->generate($url);

        $fileName = 'qrcode'.'/'.Carbon::now()->timestamp.'/'.$uuid.'.png';
        Storage::disk('public')->put($fileName, $qrpic);

        $recommend = Recommend::query()->updateOrCreate(['user_id' => $userId], [
            'user_id' => $userId,
            'uuid' => $uuid,
            'url' => '/storage/'.$fileName
        ]);

        return $this->response()->item($recommend, new RecommendQrCodeTransformer());
	}

    /**
     * 修改昵称
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function changeName($id, Request $request)
    {
        $nickName = $request->input('nick_name');
        $userId = $request['auth_user']->id;
        $cardInfo = Card::where('user_id', $userId)->where('id', $id)->first();
        if (!$cardInfo) {
            return $this->response()->errorBadRequest('该卡不存在！');
        }
        $cardInfo->other_name = $nickName;
        $cardInfo->update();
        return $this->response()->array(['message' => 'success']);
	}
}