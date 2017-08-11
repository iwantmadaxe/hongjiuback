 <?php

/**
 *
 * @api {post} /user 注册
 * @apiName 注册
 * @apiGroup User
 * @apiPermission 不需要token
 * @apiParam {String} name <code>必需</code>。姓名。
 * @apiParam {String} cardNum <code>必需</code>。卡号。
 * @apiParam {Number} phone <code>必需</code>。电话。
 * @apiParam {String} password  <code>必需</code>。密码。
 * @apiParam {String} email <code>必需</code>。邮箱。
 * @apiParam {String} openid <code>必需</code>。
 * @apiParam {String} address <code>必需</code>。地址。
 * @apiParam {String} wechat <code>必需</code>。微信公众号。
 * @apiParam {Number} smsCode  <code>必需</code>。短信验证码。

 * @apiSuccessExample 成功例子
 *
{
	"data": {
		"token": "xxxxxxxxxxxxxxxxxx",    //用户token
	}
}
 */

/**
 *
 * @api {post} /user/login 登录
 * @apiGroup User
 * @apiPermission 不需要token
 * @apiParam {Number} phone <code>必需</code>。电话。
 * @apiParam {String} auth_name <code>必需</code>。登录方式,可选local 或者 sms。
 * @apiParam {String} password  <code>可选</code>。密码, auth_name = local时必填。
 * @apiParam {Number} smsCode  <code>可选</code>。短信验证码, auth_name = sms时必填。
 * @apiSuccessExample 成功例子
 *
{
	"data": {
		"token": "xxxxxxxxxxxxxxxxxx",    //用户token
	}
}
 */

/**
 *
 * @api {put} /user/password/forget 忘记密码
 * @apiGroup User
 * @apiPermission 不需要token
 * @apiParam {Number} phone <code>必需</code>。电话。
 * @apiParam {String} password  <code>必需</code>。新的密码。
 * @apiParam {Number} smsCode  <code>必填</code>。短信验证码。
 * @apiSuccessExample 成功例子
 *
{
	"data": {
	}
}
 */


/**
 *
 * @api {get} /user/logout 退出登录
 * @apiGroup User
 * @apiPermission 不需要token
 * @apiSuccessExample 成功例子
 *
{
	"data": {
		"message": "您已退出"
	}
}
 */

/**
 *
 * @api {get} /user/profile 用户信息
 * @apiGroup User
 * @apiPermission 需要token
 * @apiSuccessExample 成功例子
 *
		{
			"data": {
			"id": 5,
			"name": "zhaohehe",
			"email": "3288299@qq.com",
			"phone": "13814248122",
			"address": "具体的地址",
			"area": {
				"province": {
					"name": "北京",
				"code": 110000
			  },
			  "city": {
					"name": "北京市",
				"code": 110100
			  },
			  "district": {
					"name": "丰台区",
				"code": 110106
			  }
			}
		  }
		}
 */

/**
 *
 * @api {put} /user 修改用户信息
 * @apiGroup User
 * @apiPermission 需要token
 * @apiParam {String} name <code>必需</code>。姓名。
 * @apiParam {String} email <code>可选</code>。邮箱。
 * @apiParam {String} address <code>必需</code>。地址。
 * @apiParam {String} areaCode <code>必需</code>。地址。
 * @apiSuccessExample 成功例子
 *
	{
		"data": {
		"message": "用户信息更新成功"
	  }
	}
 */

/**
 *
 * @api {put} /user/phone 修改用户手机号
 * @apiGroup User
 * @apiPermission 需要token
 * @apiParam {Number} phone <code>必需</code>。电话。
 * @apiParam {String} smsCode <code>必需</code>。验证码。
 * @apiSuccessExample 成功例子
 *
 *
	{
		"data": {
		"message": "手机号更新成功"
	  }
	}
 */

/**
 *
 * @api {put} /user/password 修改用户密码
 * @apiGroup User
 * @apiPermission 需要token
 * @apiParam {String} oldPassword <code>必需</code>。
 * @apiParam {String} newPassword <code>必需</code>。
 * @apiSuccessExample 成功例子
 *
	{
		"data": {
		"message": "密码更新成功"
	  }
	}
 */

/**
 *
 * @api {get} /admin/users  用户列表
 * @apiGroup User
 * @apiPermission 需要token
 * @apiSuccessExample 成功例子
 *
		{
			"data": [
			{
				"id": 10,
			  "name": "zhaohehe",
			  "email": null,
			  "phone": "13814248126",
			  "is_forbidden": 0
			}
		  ],
		  "meta": {
			"pagination": {
				"total": 1,
			  "count": 1,
			  "per_page": 15,
			  "current_page": 1,
			  "total_pages": 1,
			  "links": []
			}
		  }
		}
 */

/**
 *
 * @api {put} /admin/user/{id}/enable/{1或者0} 禁用启用
 * @apiGroup User
 * @apiPermission 需要token
 * @apiSuccessExample 成功例子
 *
	{
		"data": {
		"message": "操作成功"
	  }
	}
 */

/**
 *
 * @api {delete} /admin/user/{id} 删除用户
 * @apiGroup User
 * @apiPermission 需要token
 * @apiSuccessExample 成功例子
 *
	{
		"data": {
		"message": "操作成功"
	  }
	}
 */

/**
 *
 * @api {get} /admin/user/state 用户统计信息
 * @apiGroup User
 * @apiPermission 需要token
 * @apiSuccessExample 成功例子
 *
	{
		"data": {
		"day": 0,
		"week": 0,
		"month": 1,
		"count": 1
	  }
	}
 */

/**
 *
 * @api {get} /admin/user/increment 用户统计图表
 * @apiGroup User
 * @apiPermission 需要token
 * @apiParam {String} start_date <code>必需</code>。起始日期。
 * @apiParam {String} end_date <code>必需</code>。截止日期, 2017-5-1。
 * @apiSuccessExample 成功例子
 *
	{
		"data": [
		{
			"days": "2017-03-29",
		  "count": 1
		},
		{
			"days": "2017-04-07",
		  "count": 1
		}
	  ]
	}
 */