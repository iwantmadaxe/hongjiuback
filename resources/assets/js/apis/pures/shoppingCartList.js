function pureShoppingCartList (orders) {
	orders = orders.map(function (item) {
		let itemTpl = {};
		itemTpl.image = item.service_image;
		itemTpl.name = item.service_name;
		itemTpl.price = item.price;
		itemTpl.serviceNum = item.amount;
		itemTpl.id = item.id;
		itemTpl.sku_id = item.sku_id;
		// itemTpl.sku_id = 1;
		itemTpl.choose = false;
		itemTpl.finalPrice = '1000';
		itemTpl.finalPrice2 = '';
		itemTpl.reducePrice2 = '';
		itemTpl.status = {
			code: '',
			value: ''
		};
		itemTpl.attributes = item.attribute.map(function (item2) {
			if (item2.type === 2) {
				item2.defaultPath = {
					province: {
						name: '北京'
					},
					city: {
						name: '北京市'
					},
					district: {
						name: '北京市'
					}
				};
			}
			return item2;
		});
		return itemTpl;
	});
	return orders;
}

export default pureShoppingCartList;
