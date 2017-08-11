<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/12
 * Time: 下午5:10
 */
return [
    'telecom_api' => ['delete', 'disable', 'enable', 'start', 'stop', 'active'],
    'card_type' => [1 => '零月租卡', 2 => '季度卡', 3 => '半年卡', 4 => '年卡'],
    'package_type' => [1 => '零月租卡', 2 => '季度卡', 3 => '半年卡', 4 => '年卡', 5 => '购卡', 6 => '叠加包'],
    'sms_type' => ['register', 'login', 'password', 'updatePhone', 'bindPhone', 'validateCard'],
    'package_exchange_status' => [1 => '购卡奖励', 2 => '充值奖励', 3 => '提现扣除', 4 => '兑换扣除'],
];
