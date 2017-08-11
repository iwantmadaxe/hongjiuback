/**
* 接口错误信息统一处理
* 在需要登录验证的地方必须引入vuex的goLogout方法进行登出
*/

import localLogout from './logout.js';

const errors = function (error, _this) {
	let message = _this.$message;
	if (error.status === 500) {
		_this.$alert('系统出错了！', '提示');
		return false;
	} else if (error.status === 401) {
		_this.$alert('系统出错了！', '提示').then(function () {
			// 返回登录页面
			try {
				localLogout();
				window.location.href = '/admin/login';
			} catch (e) {}
		});
		return false;
	} else if (error.status === 0) {
		_this.$alert('网络错误！', '提示');
		return false;
	} else {
		return error.data;
	}
};

export default errors;
