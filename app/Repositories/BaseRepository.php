<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BaseRepository
{
	public $model;

	public function find($id)
	{
		return $this->model->find($id);
	}

	public function findBy($field, $value)
	{
		return $this->model->where($field, $value)->first();
	}

	public function create(array $data)
	{
		return $this->model->create($data);
	}

	protected function beginTransaction(){
		DB::beginTransaction();
	}

	protected function commit(){
		DB::commit();
	}

	protected function rollback(){
		DB::rollback();
	}
}