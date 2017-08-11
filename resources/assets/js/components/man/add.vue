<template>
	<div class="man-add">
	  	<div class="overflow-hidden">
			<div class="preload">
				<div class="mac-add dx-context">
					<div class="dozen-row clearfix">
						<label class="left control-label">输入账号：</label>
						<div class="right clearfix">
							<el-input v-model="add.username" placeholder="输入账号"></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">输入密码：</label>
						<div class="right clearfix">
							<el-input type="password" v-model="add.password" placeholder="输入密码"></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">权限：</label>
						<div class="right clearfix">
							<el-select v-model="add.auth" placeholder="请选择账号类型">
								<el-option
								v-for="item in authList"
								:key="item.value"
								:label="item.label"
								:value="item.value">
								</el-option>
							</el-select>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<div class="normal-btn">
							<el-button @click="" type="primary">确定</el-button>
							<el-button @click="cancel" type="danger">取消</el-button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
	// import apis from '../../apis/index.js';
	// import axios from 'axios';
	import { readLocal } from '../../utils/localstorage.js';

	export default {
		name: 'man-add',
		data () {
			return {
				token: '',
				add: {
					username: '',
					password: '',
					auth: ''
				},
				authList: [
					{
						label: '管理员',
						value: 1
					},
					{
						label: '超级管理员',
						value: 2
					}
				]
			};
		},
		created () {
			this.token = readLocal('user').token;
			// axios.defaults.headers.common['Authorization'] = this.token;
		},
		methods: {
			cancel () {
				window.history.go(-1);
			},
			postAdd () {
				if (!this.add.MachineName) {
					this.$alert('MachineName未填!', '提示');
					return false;
				}
				if (!this.add.MachineType) {
					this.$alert('MachineType未填!', '提示');
					return false;
				}
				if (!this.add.ServerAddress) {
					this.$alert('ServerAddress未填!', '提示');
					return false;
				}
				if (!this.add.Account) {
					this.$alert('Account未填!', '提示');
					return false;
				}
				if (!this.add.Password) {
					this.$alert('Password未填!', '提示');
					return false;
				}
				// axios.post(apis.urls.macAdd + '?token=' + this.token, this.add)
				// .then((response) => {
				// 	this.$message('添加成功！');
				// 	this.$router.push({name: 'MacList'});
				// })
				// .catch((error) => {
				// 	apis.errors.errorPublic(error.response, this);
				// });
			}
		},
		components: {
		}
	};
</script>
<style lang="scss">
	@import '../../../sass/partials/_var.scss';
	@import '../../../sass/partials/_border.scss';

</style>
