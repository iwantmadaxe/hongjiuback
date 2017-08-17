<?php
/**
 * Created by PhpStorm.
 * User: odeen
 * Date: 2017/8/5
 * Time: 上午9:43
 */

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class BaseRepository
{
    public $model;

    public function find($id,$columns=['*'])
    {
        return $this->model->find($id,$columns);
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
