<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/12
 * Time: 上午11:28
 */

namespace App\Repositories;


use App\Models\Status;
use Carbon\Carbon;

class TelecomStatusRepository extends BaseRepository
{
    public function __construct(Status $telecomStatus)
    {
        $this->model = $telecomStatus;
    }

    /**
     * 判断电信服务器是否挂了。
     * @return int
     */
    public function isDead()
    {
        $status = $this->model->where('id', 1)->first();
        // 1h内 dead 还是返回 is_dead
        if ($status && Carbon::now()->subHour()  <= $status->updated_at) {
            return $status->is_dead;
        } else {
            return 0;
        }
    }

    /**
     * 更新电信服务器挂了的信息。
     * @param int $dead
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setDead($dead = 1)
    {
       return $this->model->query()->updateOrCreate(['id'=> 1], ['is_dead' => $dead]);
    }
}