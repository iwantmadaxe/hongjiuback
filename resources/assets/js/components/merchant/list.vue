<template>
	<div class="merchant-list">
	  	<div class="overflow-hidden">
			<div class="preload">
				<div @click="singleAdd" class="btn-con">
					<add-new-btn></add-new-btn>
				</div>
				<el-table
					:data="list.data"
					style="width: 100%">
					<el-table-column
						prop="area"
						label="地区">
					</el-table-column>
					<el-table-column
						prop="phone"
						label="电话">
					</el-table-column>
					<el-table-column
						prop="tax"
						label="传真">
					</el-table-column>
					<el-table-column
						prop="address"
						label="地址">
					</el-table-column>
					<el-table-column
					  fixed="right"
					  label="操作"
					  width="160">
					  <template scope="scope">
						<el-button
							size="small"
							@click="goDelete(scope)">删除</el-button>
					  </template>
					</el-table-column>
				</el-table>
				<page-select :total="list.meta.pagination.total" :current-page="list.meta.pagination.current_page" :handle-current-change="fetchData"></page-select>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
	import AddNewBtn from '../../components/common/addNewBtn.vue';
	import PageSelect from '../../components/common/pageSelect.vue';
	// import apis from '../../apis/index.js';
	// import axios from 'axios';
	import { readLocal } from '../../utils/localstorage.js';

	export default {
		name: 'merchant-list',
		data () {
			return {
				token: '',
				loading: null,
				list: {
					data: [],
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
			// goEdit (scope) {
			// 	this.$router.push({name: 'ProductEdit', params: {id: scope.row.Id}});
			// },
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
						area: '上海',
						phone: '021-38092567',
						tax: '021-38092567',
						address: '上海市长阳路2467号12号楼405室内'
					},
					{
						id: 2,
						area: '江苏-镇江',
						phone: '0512-62490234',
						tax: '021-62490234',
						address: '镇江市'
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

	.main-container {
		.el-button {
			margin: 10px;
		}
	}

</style>
