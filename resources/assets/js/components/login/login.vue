<template>
	<div class="login wrapper no-navigation preload">
		<div class="sign-in-wrapper">
			<div class="sign-in-inner">
				<div class="login-brand text-center">
					<i class="fa fa-database m-right-xs"></i> 机器 <strong class="text-skin">后台</strong>
				</div>
				<form>
					<div class="form-group m-bottom-md">
						<input type="text" class="form-control" placeholder="账号" v-model="account">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" placeholder="密码" v-model="password">
					</div>
					<div class="m-top-md p-top-sm">
						<span @click="goLogin" class="btn btn-success block">登录</span>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
	import { requiredMe } from '../../utils/valids.js';
	import apis from '../../apis/index.js';
	import axios from 'axios';
	import { saveLocal } from '../../utils/localstorage.js';

	export default {
		name: 'dx-login',
		data () {
			return {
				account: '',
				password: '',
				valid: {
					msg: '',
					ok: true
				}
			};
		},
		created () {
			let hasAuth = this.getCookie('token');
			if (hasAuth) {
				this.$router.push({name: 'Index'});
			}
		},
		methods: {
			goLogin () {
				let _this = this;
				// 数据验证
				_this.valid = {msg: '', ok: true};
				// 验证各个所填参数必填
				if (!requiredMe(_this.account)) {
					_this.valid.msg = '账号必填！';
					_this.valid.ok = false;
					_this.$message('请填写账号！', '提示');
					return false;
				}
				if (!requiredMe(_this.password)) {
					_this.valid.msg = '密码必填！';
					_this.valid.ok = false;
					_this.$message('请填写密码！', '提示');
					return false;
				}
				let postTpl = {
					account: _this.account,
					password: _this.password
				};
				axios.post(apis.urls.login, postTpl)
				.then((response) => {
					let loginTpl = apis.pures.pureLogin(response.data.data);
					saveLocal('user', loginTpl);
					_this.$router.push({name: 'MacList'});
					window.reload();
				})
				.catch((error) => {
					apis.errors.errorPublic(error.response, _this);
				});
			},
			getCookie (name) {
				let reg = new RegExp('(^| )' + name + '=([^;]*)(;|$)');
				let arr = document.cookie.match(reg);
				if (arr) {
					return unescape(arr[2]);
				} else {
					return null;
				}
			}
		},
		components: {
		}
	};
</script>
<style lang="scss">
	@import '../../assets/sass/partials/_var.scss';
	@import '../../assets/sass/partials/_border.scss';
	
	.input-field-con {
		.mint-field {
			background-image: none;
		}
		.mt-field-con {
			margin: 0.15rem auto 0;
		}
		.login-operate {
			width: 80%;
			padding: 0 0.1rem;
			margin:  0.2rem auto 0;
		}
		.mint-cell {
			min-height: auto;
		}
		.mint-cell-wrapper {
			width: 80%;
			min-width: 2.4rem;
			margin: 0 auto;
			position: relative;
			overflow: hidden;
			border-radius: 0.2rem;
			padding: 0;
		}
		.mint-field-core {
			width: 100%;
			height: 0.35rem;
			padding: 0.1rem 0.4rem;
			line-height: 0.15rem;
			font-size: $input-text;
			background: $bg-gray;
		    -webkit-box-flex: none;
		    -ms-flex: none;
		    flex: none;
		}
		.mint-field-other {
			.mint-button  {
				height: 0.35rem;
				line-height: 0.35rem;
				font-size: $input-text;
			}
		}
		.border-right {
			@include border-right($border-gray);
		}
	}
	.mint-tab-container {
		.mint-cell-wrapper {
			background-image: none;
		}
		.mint-cell:last-child {
			background-image: none;
		}
	}
	.common-navbar {
		.mint-navbar {
			.mint-tab-item-label {
				height: 0.25rem;
				line-height: 0.25rem;
			}
			.mint-tab-item {
				padding: 0.1rem 0;
				background: $bg-gray;
				color: $color-text;
			}
			.mint-tab-item.is-selected {
				position: relative;
				margin-bottom: 0 !important;
				border-bottom: 0 !important;
				color: $color-text;
			}
			.is-selected::after {
				content: '';
				background: $color-blue;
				left: 0;
				right: 0;
				bottom: 0;
				margin-left: auto;
				margin-right: auto;
				position: absolute;
			    height: 0.03rem;
				width: 0.8rem;
			}
			.mint-tab-item-label {
				font-size: $navbar-title;
			}
		}
	}
	.bg-choose {
		.mint-field-core {
			background-image: url('../../assets/images/login/choose.png');
		    background-repeat: no-repeat;
    		background-position: 0.2rem center;
    		background-size: 0.12rem 0.14rem;
		}
	}
	.bg-card {
		.mint-field-core {
			background-image: url('../../assets/images/login/card.png');
		    background-repeat: no-repeat;
    		background-position: 0.2rem center;
    		background-size: 0.16rem 0.14rem;
		}
	}
	.bg-phone {
		.mint-field-core {
			background-image: url('../../assets/images/login/phone.png');
		    background-repeat: no-repeat;
    		background-position: 0.2rem center;
    		background-size: 0.12rem 0.14rem;
		}
	}
	.bg-password {
		.mint-field-core {
			background-image: url('../../assets/images/login/password.png');
		    background-repeat: no-repeat;
    		background-position: 0.2rem center;
    		background-size: 0.12rem 0.14rem;
		}
	}
	.bg-sms {
		.mint-field-core {
			background-image: url('../../assets/images/login/sms.png');
		    background-repeat: no-repeat;
    		background-position: 0.2rem center;
    		background-size: 0.12rem 0.14rem;
		}
	}
	.input-field-con .btn-register .mint-button {
		border-radius: 0.2rem;
		width: 80%;
		margin: 0 auto;
	}
</style>
