<?php

/**
 *
 * @api {get} /flow/balance 流量查询
 * @apiName 流量查询
 * @apiGroup Flow
 * @apiPermission 需要token

 * @apiSuccessExample 成功例子
 *
 *
		{
			"data": {
			"total": "2053",
			"used": "422.91",
			"remained": "1630.09"
		  }
		}
 */

/**
 *
 * @api {get} /flow/packages 套餐列表
 * @apiGroup Flow
 * @apiPermission 需要token
 * @apiSuccessExample 成功例子
 *
 *
		{
			"data": [
			{
				"flow_value": 12,
			  "name": "月卡",
			  "price": 10000,
			  "instruction": "多少流量"
			}
		  ]
		}
 */

/**
 *
 * @api {get} /flow/record  消费记录
 * @apiGroup Flow
 * @apiPermission 需要token

 * @apiSuccessExample 成功例子
 *
 *
	{
		"data": [
		{
			"id": 2,
		  "package_name": "套餐",
		  "package_price": 1000,
		  "end_time": "2017-04-06"
		}
	  ]
	}
 */