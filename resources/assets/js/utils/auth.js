import { saveLocal, readLocal } from './localStorage.js';
import apis from '../apis';
import axios from 'axios';

const authControl = function (call, _this) {
	let authData;
	if (readLocal('authControl')) {
		authData = readLocal('authControl');
		_this.authData = authData;
		call(_this);
		return authData;
	} else {
		axios.get(apis.urls.myPermission)
		.then((response) => {
			authData = response.data.data.map(function (item) {
				return item.id;
			});
			_this.authData = authData;
			saveLocal('authControl', authData);
			call(_this);
			return authData;
		})
		.catch((error) => {
			apis.errors.errorPublic(error.response, _this);
		});
	}
};

export default authControl;
