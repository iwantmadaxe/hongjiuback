function queryData (data) {
	let dataTpl = {};
	dataTpl.total = parseInt(data.total);
	dataTpl.used = parseInt(data.used);
	dataTpl.remained = parseInt(data.remained);
	dataTpl.card_number = parseInt(data.card_number);
	dataTpl.rate = dataTpl.remained / dataTpl.total * 100;
	return dataTpl;
}

export default queryData;
