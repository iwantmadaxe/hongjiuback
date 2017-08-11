function pureService (service) {
	service.attributes = service.attributes.map(function (item) {
		item.currentValue = item.defaultValue;
		if (item.type === 2) {
			item.defaultPath.city = {
				id: item.defaultPath.city.code,
				code: item.defaultPath.city.code,
				name: item.defaultPath.city.name
			};
			item.defaultPath.province = {
				id: item.defaultPath.province.code,
				code: item.defaultPath.province.code,
				name: item.defaultPath.province.name
			};
			item.defaultPath.district = {
				id: item.defaultPath.district.code,
				code: item.defaultPath.district.code,
				name: item.defaultPath.district.name
			};
		}
		return item;
	});
	return [service];
}

export default pureService;
