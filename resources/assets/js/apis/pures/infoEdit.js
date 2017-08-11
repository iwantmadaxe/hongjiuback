function pureInfoEdit (info) {
	if (info.name) {
		info.name = info.name;
	} else {
		info.name = '';
	}
	info.avatar = info.avatar;
	info.id = info.id;
	info.phone = info.phone;
	return info;
}

export default pureInfoEdit;
