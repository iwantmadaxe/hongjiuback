<template>
	<div class="man-list">
	  	<div class="overflow-hidden">
			<div class="preload">
				<div @click="singleAdd" class="btn-con">
					<add-new-btn></add-new-btn>
				</div>
				<el-table
					:data="list.data"
					style="width: 100%">
					<el-table-column
						prop="username"
						label="用户名">
					</el-table-column>
					<el-table-column
						prop="level"
						label="等级">
					</el-table-column>
					<el-table-column
					  fixed="right"
					  label="操作"
					  width="160">
					  <template scope="scope">
						<el-button
							size="small"
							@click="">重置密码</el-button>
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
	import AddNewBtn from '../../components/common/addNewBtn.vue';
	import PageSelect from '../../components/common/pageSelect.vue';
	// import apis from '../../apis/index.js';
	// import axios from 'axios';
	import { readLocal } from '../../utils/localstorage.js';

	export default {
		name: 'man-list',
		data () {
			return {
				token: '',
				loading: null,
				list: {
					data: [{
						username: '',
						level: ''
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
				window.location.href = './edit';
				// this.$router.push({name: 'ManEdit', params: {id: scope.row.Id}});
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
						username: 'root12345',
						level: '超级管理员'
					},
					{
						id: 2,
						username: 'admin',
						level: '管理员'
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

</style>
