/**
* 格式化api接口
*/
import Config from '../config.js';
import ApiRoutes from './routes.js';

const makeUrl = function (port, tplUrl) {
	return port.route + port.version + tplUrl;
};

const makeUrlList = function (apis) {
	let apiUrls = {};
	for (let tplApi in apis) {
		apiUrls[tplApi] = makeUrl(Config, apis[tplApi]);
	}
	return apiUrls;
};

let urls = makeUrlList(ApiRoutes);
export default urls;
