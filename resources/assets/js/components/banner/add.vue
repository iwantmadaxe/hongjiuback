<template>
	<div class="banner-add">
	  	<div class="overflow-hidden">
			<div class="preload">
				<div class="mac-add dx-context">
					<div class="dozen-row clearfix">
						<label class="left control-label">banner：</label>
						<el-upload
							class="avatar-uploader"
							action=""
							:show-file-list="false"
							:on-change="handleAvatarSuccess1">
							<img v-if="add.bannerUrl" :src="add.bannerUrl" class="avatar">
							<i v-else class="el-icon-plus avatar-uploader-icon"></i>
						</el-upload>
						<span class="upload-exp">图片尺寸：1000*450  支持png、jpg、gif等图片格式</span>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">&nbsp;</label>
						<div class="right clearfix imgurl">
							<el-input v-model="add.bannerUrlText" placeholder="URL"></el-input>
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
		name: 'banner-add',
		data () {
			return {
				token: '',
				add: {
					bannerUrl: '',
					bannerUrlText: ''
				}
			};
		},
		created () {
			this.token = readLocal('user').token;
			// axios.defaults.headers.common['Authorization'] = this.token;
		},
		methods: {
			handleAvatarSuccess1 (res, file) {
				this.add.bannerUrl = res.url;
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
	@import '../../../sass/partials/_var.scss';
	@import '../../../sass/partials/_border.scss';

</style>
