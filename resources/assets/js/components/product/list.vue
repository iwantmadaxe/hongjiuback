<template>
	<div class="product-list">
	  	<div class="overflow-hidden">
			<div class="preload">
				<div @click="singleAdd" class="btn-con">
					<add-new-btn></add-new-btn>
				</div>
				<el-table
					:data="list.data"
					style="width: 100%">
					<el-table-column
						label="图片">
						<template scope="scope">
							<img v-bind:src="scope.row.img" />
						</template>
					</el-table-column>
					<el-table-column
						prop="name"
						label="品名">
					</el-table-column>
					<el-table-column
						prop="price"
						label="价格">
					</el-table-column>
					<el-table-column
						prop="area"
						label="产地">
					</el-table-column>
					<el-table-column
						prop="level"
						label="等级">
					</el-table-column>
					<el-table-column
						prop="type"
						label="类型">
					</el-table-column>
					<el-table-column
						prop="address"
						label="产区">
					</el-table-column>
					<el-table-column
					  fixed="right"
					  label="操作"
					  width="160">
					  <template scope="scope">
						<el-button
							size="small"
							@click="goDelete(scope)">删除</el-button>
						<el-button
							size="small"
							@click="goEdit(scope)">编辑</el-button>
					  </template>
					</el-table-column>
				</el-table>
				<page-select :total="list.meta.pagination.total" :current-page="list.meta.pagination.current_page" :handle-current-change="fetchData"></page-select>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
	import AddNewBtn from '../common/addNewBtn.vue';
	import PageSelect from '../../components/common/pageSelect.vue';
	// import apis from '../../apis/index.js';
	// import axios from 'axios';
	import { readLocal } from '../../utils/localstorage.js';

	export default {
		name: 'pruduct-list',
		data () {
			return {
				token: '',
				loading: null,
				list: {
					data: [{
						img: '',
						name: '',
						price: '',
						area: '',
						level: '',
						type: '',
						address: ''
					}],
					meta: {
						pagination: {
							links: []
						}
					}
				}
			};
		},
		created () {
			this.token = readLocal('user').token;
			// axios.defaults.headers.common['Authorization'] = this.token;
			this.fetchData(1);
		},
		methods: {
			singleAdd () {
				window.location.href = './add';
			},
			goEdit (scope) {
				this.$router.push({name: 'ProductEdit', params: {id: scope.row.Id}});
			},
			goDelete (scope) {
				this.list.data.splice(scope.row.$index, 1);
				this.$message('删除成功!');
			},
			fetchData (p) {
				// this.loading = this.$loading({text: '加载中...'});
				// 获取机器列表
				this.list.data = [
					{
						id: 1,
						img: 'static/img/product1.jpeg',
						name: '克林伯瑞桃红',
						price: '298.00',
						area: '法国',
						level: 'VDF',
						type: '干型葡萄酒',
						address: '奥克'
					},
					{
						id: 2,
						img: 'static/img/product2.jpeg',
						name: '克林伯瑞干白',
						price: '398.00',
						area: '法国',
						level: 'VDF',
						type: '干型葡萄酒',
						address: '奥克'
					}
				];
			}
		},
		components: {
			[AddNewBtn.name]: AddNewBtn,
			[PageSelect.name]: PageSelect
		}
	};
</script>
<style lang="scss">
	@import '../../../sass/partials/_var.scss';
	@import '../../../sass/partials/_border.scss';
	
	.dx-context {
		background: #fff;
	}
	.m-top-md {
		background: #fff;
	}
	.main-container {
		.el-button {
			margin: 10px;
		}
	}
	.el-table {
		img {
			margin: 10px 0;
		}
	}
	.sidebar-menu {
		li {
			cursor: pointer;
		}
	}
</style>
