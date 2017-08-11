import errors from './errors.js';
// import { MessageBox } from 'mint-ui';
const errorRegister = function (response, _this) {
	if (errors(response, _this) === false) {
		return false;
	}
	// 格式错误提示
	if (response.status === 422) {
		_this.$alert(response.data.message, '提示');
		return false;
	}
	// 普通错误提示
	_this.$alert(response.data.message, '提示');
	return false;
};

export default errorRegister;
