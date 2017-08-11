let port = {};

if (is_pro) {
	port = {
		route: 'http://telecom.odinsoft.com.cn/',
		version: 'api/v1/'
	};
} else {
	port = {
		route: '/',
		version: 'api/v1/'
	};
}

export default port;
