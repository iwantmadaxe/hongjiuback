<template>
	<div class="product-edit">
	  	<div class="overflow-hidden">
			<div class="preload">
				<h1>商品编辑</h1>
				<div class="mac-add dx-context">
					<div class="dozen-row clearfix">
						<label class="left control-label">商品名称：</label>
						<div class="right clearfix">
							<el-input v-model="add.name" placeholder="输入商品名称"></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">统一零售价：</label>
						<div class="right clearfix">
							<el-input v-model="add.price" placeholder="输入价格"></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">含量：</label>
						<div class="right clearfix">
							<el-input v-model="add.value" placeholder="输入含量"></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">产地：</label>
						<div class="right clearfix">
							<el-input v-model="add.area" placeholder="输入产地"></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">等级：</label>
						<div class="right clearfix">
							<el-select v-model="add.level" placeholder="请选择等级">
								<el-option
								v-for="item in levelList"
								:key="item.value"
								:label="item.label"
								:value="item.value">
								</el-option>
							</el-select>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">类型：</label>
						<div class="right clearfix">
							<el-input v-model="add.type" placeholder="输入类型"></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">产区：</label>
						<div class="right clearfix">
							<el-input v-model="add.address" placeholder="输入产区"></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix temp">
						<label class="left control-label">品尝温度：</label>
						<div class="right clearfix">
							<el-input v-model="add.temMin" placeholder="输入温度"></el-input>
							<hr class="text" />
							<el-input v-model="add.temMax" placeholder="输入温度"></el-input>
						</div>
					</div>
					<div class="dozen-row clearfix temp">
						<label class="left control-label">商品：</label>
						<el-upload
							class="avatar-uploader"
							action=""
							:show-file-list="false"
							:on-change="handleAvatarSuccess">
							<img v-if="add.imageUrl" :src="add.imageUrl" class="avatar">
							<i v-else class="el-icon-plus avatar-uploader-icon"></i>
						</el-upload>
					</div>
					<div class="dozen-row clearfix">
						<label class="left control-label">&nbsp;</label>
						<div class="right clearfix imgurl">
							<el-input v-model="add.imgUrlText" placeholder="URL"></el-input>
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
		name: 'pruduct-edit',
		data () {
			return {
				token: '',
				add: {
					img: '',
					name: '',
					price: '',
					value: '',
					area: '',
					level: '',
					type: '',
					address: '',
					temMin: '',
					temMax: '',
					imageUrl: '',
					imageUrlText: ''
				},
				levelList: [
					{
						label: '1',
						value: 1
					},
					{
						label: '2',
						value: 2
					},
					{
						label: '3',
						value: 3
					},
					{
						label: '4',
						value: 4
					}
				]
			};
		},
		created () {
			this.token = readLocal('user').token;
			// axios.defaults.headers.common['Authorization'] = this.token;
		},
		methods: {
			handleAvatarSuccess (res, file) {
				this.add.imageUrl = res.url;
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
