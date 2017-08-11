<template>
	<div class="product-add">
	  	<div class="overflow-hidden">
			<div class="preload">
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
		name: 'pruduct-add',
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
	.avatar-uploader .el-upload__input {
		display: none;
	}
	.avatar-uploader .el-upload {
		border: 1px dashed #d9d9d9;
		border-radius: 6px;
		cursor: pointer;
		position: relative;
		overflow: hidden;
	}
	.avatar-uploader .el-upload:hover {
		border-color: #20a0ff;
	}
	.avatar-uploader-icon {
		font-size: 28px;
		color: #8c939d;
		width: 178px;
		height: 178px;
		line-height: 178px;
		text-align: center;
	}
	.avatar {
		width: 178px;
		height: 178px;
		display: block;
	}
	.product-add {
		.temp {
			.el-input {
				width: auto;
				margin-right: 20px;
				float: left;
			}
			.text {
			    width: 20px;
			    float: left;
			    background: #333;
			    height: 1px;
			    margin: 18px 20px 0 0;
			}
		}
	}
	.dx-context {
		.dozen-row {
			.el-input__inner {
				width: 200px;
			}
			.imgurl {
				.el-input__inner {
					width: 400px;
				}
			}
		}
	}
	.dx-context {
		padding: 15px 0;
		.el-button {
			margin-left: 10px;
			margin-bottom: 10px;
		}
		.filename {
			padding: 0 20px;
		}
		.label-type2 {
			margin-top: 10px;
		}
		.charts-row {
			margin: 10px 0;
			padding: 0 15px;
			position: relative;
			.select-text {
			    position: absolute;
			    left: 150px;
			    height: 36px;
			    line-height: 36px;
			    display: block;
			    z-index: 20;
		        font-size: 14px;
		        color: #000;
			}
			.add-new-btn {
				position: relative;
				.upload-xls {
					width: 88px;
					height: 36px;
					position: absolute;
					top: 0;
					left: 0;
					opacity: 0;
				}
			}
			.left {
				width: 120px;
				float: left;
			    margin-top: 10px;
			    display: block;
			}
			.right {
				width: 200px;
				float: left;
			}
			.el-dropdown {
				border: 1px solid #48576a;
				border-radius: 5px;
				padding: 0 10px;
				height: 36px;
    			line-height: 36px;
			}
		}
		.search-row {
			.el-dropdown {
				margin-left: 10px;
				height: 36px;
    			line-height: 36px;
			}
			.search-con {
				float: left;
				margin: 0 10px 15px 0;
				position: relative;
				.unit {
					position: absolute;
					top: 0;
				    right: 5px;
				    height: 36px;
				    line-height: 36px;
				}
				.topic {
					padding: 0 10px;
					font-size: 14px;
					font-weight: 600;
					float: left;
    				display: block;
				    height: 36px;
				    line-height: 36px;
				    width: auto;
				}
				.el-input {
					float: left;
    				display: block;
    				width: 160px;
					.el-input__inner {
						width: 100%;
						float: left;
	    				display: block;
					}
				}
			}
		}
		.dozen-row {
			margin: 10px 0;
			padding: 0 15px;
			position: relative;
			.select-text {
			    position: absolute;
			    left: 220px;
			    height: 36px;
			    line-height: 36px;
			    display: block;
			    z-index: 20;
		        font-size: 14px;
		        color: #000;
			}
			.add-new-btn {
				position: relative;
				.upload-xls {
					width: 88px;
					height: 36px;
					position: absolute;
					top: 0;
					left: 0;
					opacity: 0;
				}
			}
			.left {
				width: 15%;
				float: left;
			    margin-top: 10px;
			}
			.right {
				width: 85%;
				float: left;
			}
			.el-dropdown {
				border: 1px solid #48576a;
				border-radius: 5px;
				padding: 0 10px;
				height: 36px;
    			line-height: 36px;
			}
			p {

			}
		}
	}
</style>
