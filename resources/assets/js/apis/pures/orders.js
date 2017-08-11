function pureOrders (orders) {
	orders = orders.map(function (item) {
		let itemTpl = {};
		itemTpl.image = item.services.service_image;
		itemTpl.name = item.services.service_name;
		itemTpl.price = item.services.service_price;
		itemTpl.finalPrice = item.final_price;
		itemTpl.serviceNum = item.services.amount;
		itemTpl.status = {
			// code: item.status.code,
			code: 2,  //  测试用
			value: item.status.value
		};
		itemTpl.id = item.id;
		itemTpl.contactId = item.contact_id;
		itemTpl.orderTime = item.order_time;
		itemTpl.attributes = item.services.attribute.map(function (item2) {
			if (item2.key !== '服务地区') {
				item2.type = item2.type;
				item2.name = item2.key;
				item2.defaultValue = 1;
				item2.option = [{
					name: item2.value,
					id: 1
				}];
			} else if (item2.key === '服务地区') {
				item2.type = item2.type;
				item2.name = item2.key;
				item2.defaultValue = 1;
				item2.defaultPath = {
					province: {
						name: '',
						code: ''
					},
					city: {
						name: '',
						code: ''
					},
					district: {
						name: '',
						code: ''
					}
				};
				if (item2.value) {
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
			}
			return item2;
		});
		return itemTpl;
	});
	return orders;
}

export default pureOrders;
