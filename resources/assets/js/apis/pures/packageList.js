function purePackageList (list) {
	let listTpl = [];
	listTpl = list.map(function (item) {
		item.choose = false;
		return item;
	});
	return listTpl;
}

export default purePackageList;
