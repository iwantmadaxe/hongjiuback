/**
* 注册localstorage事件，加入超时时间。
*/
let expiration = 600;    // 默认过期时间10分钟的秒数

// 保存数据
const saveLocal = function (head, data, exp) {
	if (!window.localStorage) return false;    // localStorage不支持则不使用
	let expirationTime = expiration;
	if (typeof exp === 'number') {
		expirationTime = exp;
	}
	expirationTime = expirationTime * 1000;
	let res = {};
	let tplHead = 'odinProMb:' + head;
	res.data = data;
	res.expiration = +new Date() + expirationTime;
	window.localStorage.setItem(tplHead, JSON.stringify(res));
	return true;
};

// 读取数据
const readLocal = function (head) {
	let tplHead = 'odinProMb:' + head;
	let data = '';
	if (!window.localStorage) return false;    // localStorage不支持则不使用
	data = window.localStorage.getItem(tplHead);
	data = data ? JSON.parse(data) : {};
	// 超时返回false
	if (!data.hasOwnProperty('expiration') || data.expiration < +new Date()) {
		window.localStorage.removeItem(tplHead);
		return false;
	}
	return data.data;
};

// 清除指定数据
const clearLocal = function (head) {
	let tplHead = 'odinProMb:' + head;
	if (!window.localStorage) return false;    // localStorage不支持则不使用
	window.localStorage.removeItem(tplHead);
	return true;
};

// 清空数据
const clearAllLocal = function () {
	if (!window.localStorage) return false;    // localStorage不支持则不使用
	window.localStorage.clear();
	return true;
};

export { saveLocal, readLocal, clearLocal, clearAllLocal };
