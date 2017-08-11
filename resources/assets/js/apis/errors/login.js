import errors from './errors.js';
// import { MessageBox } from 'mint-ui';

const errorLogin = function (response, _this) {
	if (errors(response, _this) === false) {
		return false;
	}
	// 处理具体错误
	// 格式错误提示
	if (response.status === 422) {
		if (response.data.errors.hasOwnProperty('phone')) {
			_this.$alert(response.data.errors.phone[0], '提示');
			return false;
		}
		if (response.data.errors.hasOwnProperty('password')) {
			_this.$alert(response.data.errors.password[0], '提示');
			return false;
		}
		_this.$alert('帐号密码不匹配!', '提示');
		return false;
	}
	// 普通错误提示
	_this.$alert(response.data.message, '提示');
	return false;
};

export default errorLogin;
