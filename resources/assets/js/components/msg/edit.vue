<template>
	<div class="msg-edit">
	  	<div class="overflow-hidden">
			<div class="preload">
				<div class="mac-add dx-context">
					<el-upload
						class="avatar-uploader"
						action=""
						:show-file-list="false"
						:on-change="handleAvatarSuccess">
						<img v-if="imageUrl" :src="imageUrl" class="avatar">
						<i v-else class="el-icon-plus avatar-uploader-icon"></i>
					</el-upload>
					<div class="dozen-row clearfix">
						<label class="left control-label">品名：</label>
						<div class="right clearfix">
							<el-input v-model="add.name" placeholder=""></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">价格：</label>
						<div class="right clearfix">
							<el-input v-model="add.price" placeholder=""></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">产地：</label>
						<div class="right clearfix">
							<el-input v-model="add.area" placeholder=""></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">等级：</label>
						<div class="right clearfix">
							<el-input v-model="add.level" placeholder=""></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">类型：</label>
						<div class="right clearfix">
							<el-input v-model="add.type" placeholder=""></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">产区：</label>
						<div class="right clearfix">
							<el-input v-model="add.address" placeholder=""></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<div class="normal-btn">
							<el-button @click="postAdd" type="primary">确定</el-button>
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
		name: 'msg-edit',
		data () {
			return {
				token: '',
				add: {
					img: '',
					name: '克林伯瑞干白',
					price: '398.00',
					area: '法国',
					level: 'VDF',
					type: '干型葡萄酒',
					address: '奥克'
				},
				id: '',
				imageUrl: ''
			};
		},
		created () {
			this.token = readLocal('user').token;
			this.id = this.$route.params.id;
			// axios.defaults.headers.common['Authorization'] = this.token;
		},
		methods: {
			handleAvatarSuccess (res, file) {
				this.imageUrl = res.url;
			},
			beforeAvatarUpload (file) {
				const isJPG = file.type === 'image/jpeg';
				const isLt2M = file.size / 1024 / 1024 < 2;
				// if (!isJPG) {
				// this.$message.error('上传头像图片只能是 JPG 格式!');
				// }
				if (!isLt2M) {
					this.$message.error('上传头像图片大小不能超过 2MB!');
				}
				return isJPG && isLt2M;
			},
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
</style>
