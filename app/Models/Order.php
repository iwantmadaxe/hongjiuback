<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    		'out_trade_no',
			'code',
			'user_id',
            'address_id',
			'card_id',
			'overdue',
			'card_agent', // admin.id => 卡导入的agent
			'money_agent', // agent_info.id => 实际收钱的agent
            'agent_name',
            'agent_cut_info',
			'package_id',
            'amount', // 数量，默认1
			'cut_discount',
			'cut_price',
			'prepayment',    //是否续下个月的
			'prepayment_fee',
			'overdue_bill',    //是否复机的凭证
			'total_fee',
			'favourable_price',
			'status',     //未支付状态
            'recommender', // 推荐人
            'package_type', // 开套餐是否成功，0否，1是，2非套餐
	];

    public function receiveAgent()
    {
        return $this->belongsTo('App\Models\AgentInfo', 'money_agent');
    }

    public function packageInfo()
    {
        return $this->belongsTo('App\Models\Package', 'package_id');
    }

    public function card()
    {
        return $this->belongsTo('App\Models\Card', 'card_id');
    }

    public function orderPackage()
    {
        return $this->hasOne('App\Models\OrderPackage', 'order_id');
    }

    public function isBuyCard()
    {
        return $this->packageInfo()->where('status', 1)->first()->type == 5;
    }

    public function address()
    {
        return $this->belongsTo('App\Models\DeliveryAddress', 'address_id');
    }
}
