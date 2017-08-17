let port = {};

if (is_pro) {
	port = {
		route: 'http://localhost/',
		version: 'api/v1/'
	};
} else {
	port = {
		route: '/',
		version: 'api/v1/'
	};
}

export default port;
