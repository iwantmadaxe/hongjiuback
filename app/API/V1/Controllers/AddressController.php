<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/24
 * Time: 下午3:49
 */

namespace App\API\V1\Controllers;


use App\API\V1\BaseController;
use App\API\V1\Requests\AddressCreateRequest;
use App\Models\DeliveryAddress;
use App\Transformers\AddressListTransformer;
use Illuminate\Http\Request;

class AddressController extends BaseController
{
    /**
     * 地址列表列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $userId = $request['auth_user']->id;
        $address = DeliveryAddress::where('user_id', $userId)->get();

        return $this->response()->collection($address, new AddressListTransformer());
    }

    /**
     * 添加收货地址
     * @param AddressCreateRequest $request
     * @return mixed
     */
    public function create(AddressCreateRequest $request)
    {
        $userId = $request['auth_user']->id;
        // 判断是否需要设置默认地址（第一次地址直接设置）
        $isExist = DeliveryAddress::where('user_id', $userId)->count();
        $default = 0;
        $isExist || $default = 1;

        $params = [
            'user_id' => $userId,
            'receiver' => $request->input('receiver'),
            'contact' => $request->input('contact'),
            'area' => $request->input('area'),
            'address' => $request->input('address'),
            'default' => $default
        ];
        $info = DeliveryAddress::create($params);
        if ($info) {
            return $this->response()->array(['message' => 'success']);
        }

        return $this->response()->errorBadRequest('保存失败');
    }

    /**
     * 编辑收货地址展现页面
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function edit($id, Request $request)
    {
        $userId = $request['auth_user']->id;
        $address = DeliveryAddress::where('user_id', $userId)->where('id', $id)->first();

        return $this->response()->item($address, new AddressListTransformer());
    }

    /**
     * 更新收货地址
     * @param $id
     * @param AddressCreateRequest $request
     * @return mixed
     */
    public function update($id, AddressCreateRequest $request)
    {
        $userId = $request['auth_user']->id;
        $default = $request->input('default', 0);

        $params = [
            'user_id' => $userId,
            'receiver' => $request->input('receiver'),
            'contact' => $request->input('contact'),
            'area' => $request->input('area'),
            'address' => $request->input('address'),
            'default' => $default
        ];
        $info = DeliveryAddress::where('id', $id)->update($params);
        if ($info) {
            return $this->response()->array(['message' => 'success']);
        }

        return $this->response()->errorBadRequest('更新失败');
    }

    /**
     * 删除收货地址
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function delete($id, Request $request)
    {
        $userId = $request['auth_user']->id;
        $info = DeliveryAddress::where('id', $id)->where('user_id', $userId)->delete();

        if ($info) {
            return $this->response()->array(['message' => 'success']);
        }

        return $this->response()->errorBadRequest('删除失败！');
    }

    /**
     * 设置默认地址
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function setDefault($id, Request $request)
    {
        $userId = $request['auth_user']->id;

        $lastAddress = DeliveryAddress::where('user_id', $userId)
            ->where('default', 1)->first();

        $info = DeliveryAddress::where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'default' => 1
            ]);

        if ($info) {
            if ($lastAddress) {
                $lastAddress->update(['default' => 0]);
            }
            return $this->response()->array(['message' => 'success']);
        }

        return $this->response()->errorBadRequest('删除失败！');
    }

    /**
     * 获取默认地址信息
     * @param Request $request
     * @return mixed
     */
    public function getDefault(Request $request)
    {
        $userId = $request['auth_user']->id;
        $lastAddress = DeliveryAddress::where('user_id', $userId)
            ->where('default', 1)->first();

        return $this->response()->item($lastAddress, new AddressListTransformer());
    }
}