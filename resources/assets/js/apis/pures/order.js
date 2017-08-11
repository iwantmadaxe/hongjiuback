function pureOrder (order) {
	let itemTpl = {};
	itemTpl.image = order.services.service_image;
	itemTpl.name = order.services.service_name;
	itemTpl.price = order.services.service_price;
	itemTpl.finalPrice = order.final_price;
	itemTpl.serviceNum = order.services.amount;
	itemTpl.status = order.status;
	itemTpl.id = order.id;
	itemTpl.status.code = null;  //  测试用
	// itemTpl.other = true;  //  订单详情里的其他服务开启
	itemTpl.contactId = order.contact_id;
	itemTpl.orderTime = order.order_time;
	itemTpl.attributes = order.services.attribute.map(function (item2) {
		if (item2.key !== '服务地区') {
			item2.type = 1;
			item2.name = item2.key;
			item2.defaultValue = 1;
			item2.option = [{
				name: item2.value,
				id: 1
			}];
		} else if (item2.key === '服务地区') {
			item2.type = 2;
			item2.name = item2.key;
			item2.defaultValue = 1;
			item2.defaultPath = {
				province: {
					name: item2.value.province.name,
					code: item2.value.province.code
				},
				city: {
					name: item2.value.city.name,
					code: item2.value.city.code
				},
				district: {
					name: item2.value.district.name,
					code: item2.value.district.code
				}
			};
		}
		return item2;
	});
	return itemTpl;
}

export default pureOrder;
