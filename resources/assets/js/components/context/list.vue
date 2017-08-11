<template>
	<div class="context-list">
	  	<div class="overflow-hidden">
			<div class="preload">
				<div @click="singleAdd" class="btn-con">
					<add-new-btn></add-new-btn>
				</div>
				<el-table
					:data="list.data"
					style="width: 100%">
					<el-table-column
						label="预览">
						<template scope="scope">
							<img v-bind:src="scope.row.img" />
						</template>
					</el-table-column>
					<el-table-column
						prop="topic"
						label="标题">
					</el-table-column>
					<el-table-column
						prop="type"
						label="状态">
					</el-table-column>
					<el-table-column
						prop="time"
						label="上传时间">
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
		name: 'context-list',
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
						img: 'static/img/jiang1.jpeg',
						topic: '女人与红酒',
						type: '葡萄酒常识',
						time: '2017-6-27'
					},
					{
						id: 2,
						img: 'static/img/jiang2.jpeg',
						topic: '如何辨别红酒的好坏',
						type: '葡萄酒常识',
						time: '2017-6-26'
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
