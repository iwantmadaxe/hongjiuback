<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Psy\Util\Json;

/**
 * 后台控制器父类
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 逻辑出错返回
     * @param $code
     * @param $statusCode
     * @param string $message
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($code,$statusCode,$message='',$errors=[])
    {
        $result = ['code'=>$code,'status_code'=>$statusCode,'message'=>$message];
        if(!empty($errors)){
            $result['errors'] = $errors;
        }
        return \Response::json($result,$statusCode);
    }

    /**
     * 逻辑成功返回
     * @param $data
     * @param null $meta
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data,$meta = null)
    {
        $result = ['data'=>$data];
        if(null !== $meta){
            $result['meta'] = $meta;
        }
        return \Response::json($result);
    }

    /**
     * 返回分页页面处理结果
     * @param Paginator $page
     * $param array $jsoned
     * @return \Illuminate\Http\JsonResponse
     */
    public function responsePage($page,$jsoned=[])
    {
        $page = $page->toArray();
        $result = [];
        if(!empty($jsoned) && !empty($page['data'])){
            foreach ($page['data'] as $k => $item){
                foreach ($jsoned as $jsoned_item){
                    $page['data'][$k][$jsoned_item] = json_decode($item[$jsoned_item]);
                }
            }
        }
        $result['data'] = $page['data'];
        unset($page['data']);
        $meta = new \stdClass();
        $meta->pagination = $page;
        $result['meta'] = $meta;
        return \Response::json($result);

    }


}
