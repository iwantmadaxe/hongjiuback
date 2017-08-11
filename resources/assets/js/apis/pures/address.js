/**
* 全列表的返回参数
*/
let backInfo = {
	items: []
};

function pureAddressList (info) {
	backInfo.items = info.map(function (itemTpl) {
		let item = {
			id: null,
			code: null,
			name: ''
		};
		item.id = itemTpl.code;
		item.code = itemTpl.code;
		item.name = itemTpl.name;
		return item;
	});
	return backInfo;
}

export default pureAddressList;
