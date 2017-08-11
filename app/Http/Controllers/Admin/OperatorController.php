<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Http\Requests\OperatorCreateRequest;
use App\Http\Requests\OperatorUpdatedRequest;
use App\Models\Operator;
use App\Transformers\OperatorListTransformer;

class OperatorController extends BaseController
{
	private $operator;

	public function __construct(Operator $operator)
	{
		$this->operator = $operator;
	}

	public function getList(OperatorListTransformer $listTransformer)
	{
		$operators = $this->operator->all();
		return $this->response()->collection($operators, $listTransformer);
	}

	public function index()
	{
		$operators = $this->operator->all()->pluck('name', 'id');
		return $this->response()->array(['data' => $operators]);
 	}

 	public function create(OperatorCreateRequest $request)
	{
		$operator = $request->all();
		if ($this->operator->create($operator)) {
			return $this->response()->array(['data' => ['message' => '创建运营商成功']]);
		}
	}

	public function show($id, OperatorListTransformer $listTransformer)
	{
		$operator = $this->operator->find($id);
		return $this->response()->item($operator,$listTransformer);
	}

	public function update($id, OperatorUpdatedRequest $request)
	{
		$operator = $this->operator->find($id);
		if ($operator->update($request->all())) {
			return $this->response()->array(['data' => ['message' => '修改运营商成功']]);
		}
	}
}