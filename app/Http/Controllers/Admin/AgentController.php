<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Http\Requests\AgentCancelRequest;
use App\Http\Requests\AgentCreateRequest;
use App\Http\Requests\AgentUpdateRequest;
use App\Models\Admin;
use App\Models\AgentInfo;
use App\Repositories\AgentRepository;
use App\Transformers\AgentListTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends BaseController
{
	private $agentRepository;

	public function __construct(AgentRepository $agentRepository)
	{
		$this->agentRepository = $agentRepository;
	}

	public function getList()
	{
	    $admin = Auth::guard('admin')->user();
        $agents = [];
	    // 根据权限获取自己及自己隶属的代理商 && 管理员可以获取全部数据
        if ($admin->roles->first() && str_contains($admin->roles->first()->name, 'manager')) {
            Admin::with('agent')->has('agent', '>', 0)->get()->each(function ($v) use (&$agents) {
                $agents[$v['agent']['user_id']] = $v['agent']['name'];
            });
        } else {
            $ownInfo = Admin::with('agent')->where('id', $admin->id)->has('agent', '>', 0)->first();
            if ($ownInfo) {
                $agents[$ownInfo['agent']['user_id']] = $ownInfo['agent']['name'];
                $next= $ownInfo['agent']['parentAgent'];
                while ($next) {
                    $agents[$next['user_id']] = $next['name'];
                    $next = $next['parentAgent'];
                }
            }
        }
		return $this->response()->array(['data' => $agents]);
	}

	public function create(AgentCreateRequest $request)
	{
		$agent = $request->all();
		$agent['parent_id'] = '';
		if ($this->agentRepository->createAgent($agent)) {
			return $this->response()->array(['data' => ['message' => '创建成功']]);
		} else {
		    return $this->response()->errorBadRequest('保存失败');
        }
	}

	public function index(AgentListTransformer $listTransformer)
	{
		$agents = $this->agentRepository->getAgents();
		return $this->response()->paginator($agents, $listTransformer);
	}

	public function show($id, AgentListTransformer $listTransformer)
	{
		$agent = $this->agentRepository->get($id);
		return $this->response()->item($agent, $listTransformer);
	}

	public function update($id, AgentUpdateRequest $request)
	{
		if ($this->agentRepository->updateAgent($request->all(), $id)) {
			return $this->response()->array(['data' => ['message' => '修改成功']]);
		}
	}

	public function cancel(AgentCancelRequest $request)
	{
		foreach ($request['ids'] as $key => $id) {
			$agentInfo = AgentInfo::where('user_id', $id)->first()->update(['status' => 2]);   //2 表示取消
		}
		return $this->response()->array(['data' => ['message' => '修改成功']]);
	}

	public function wechatMenu(Request $request)
	{
		//检查代理商是否有公众号
		$agent_id = $request['agent_id'];
		$agent_info = Admin::find($agent_id)->agent()->first();
		if ($agent_info && $agent_info->has_wechat) {
			$config = [
				'app_id' => $agent_info->app_id,
				'secret' => $agent_info->app_secret,
			];
			$app = new \EasyWeChat\Foundation\Application($config);
			$menu = $app->menu;

			$buttons = [
				[
					"type" => "view",
					"name" => "电信卡",
					"url"  => "http://telecom.odinsoft.com.cn/home/wechat_".$agent_info->id,
				],
			];
			if ($menu->add($buttons)->errcode == 0) {
				return $this->response()->array(['data' => ['message' => '生成成功']]);
			}
		} else {
			return $this->response()->error('该代理商没有公众号', 400, 400123);
		}
	}

	public function addAgent($id, AgentCreateRequest $request)
	{
		$agent = $request->all();
		$agent['parent_id'] = $id;
		// 判断代理商层级，最多3级
        $num = $this->agentRepository->parentsNumBy($id);
        if ($num > 2) {
            return $this->response()->errorBadRequest('根据法规，代理最多3级！');
        }
		if ($this->agentRepository->createAgent($agent)) {
			return $this->response()->array(['data' => ['message' => '创建成功']]);
		}

		return $this->response()->errorBadRequest('保存失败');
	}
}