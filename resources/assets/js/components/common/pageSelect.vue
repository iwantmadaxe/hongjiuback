<template>
	<div class="block page-select">
		<el-pagination
			@size-change="handleSizeChange"
			@current-change="handleCurrentChange"
			:current-page="currentPage"
			:page-sizes="[15, 30, 50, 100]"
			:page-size="pageSize"
			layout="total, sizes, prev, pager, next, jumper"
			:total="total">
		</el-pagination>
	</div>
</template>

<script>
export default {
	name: 'page-select',
	data () {
		return {
			pageSize: 15
		};
	},
	props: {
		currentPage: {
			type: Number,
			default: 1
		},
		total: {
			type: Number,
			default: 1
		},
		handleCurrentChange: {
			default: null
		}
	},
	methods: {
		handleSizeChange (val) {
			this.pageSize = val;
		}
	}
};
</script>
<style lang="scss">
	@import '../../../sass/_variables.scss';
	@import '../../../sass/app.scss';

	.add-new-btn {
		padding: 5 10px;
		color: $blue;
		border-color: $blue;
		font-size: $square-title;
	}
</style>
