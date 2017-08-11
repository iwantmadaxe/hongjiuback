<template>
	<div class="add-new-btn">
		<el-button :plain="plain" :type="type">{{text}}</el-button>
	</div>
</template>

<script>
	export default {
		name: 'add-new-btn',
		data () {
			return {
			};
		},
		props: {
			text: {
				type: String,
				default: '+    新增'
			},
			type: {
				type: String,
				default: ''
			},
			plain: {
				type: Boolean,
				default: false
			}
		}
	};
</script>
<style lang="scss">
	@import '../../../sass/_variables.scss';
	@import '../../../sass/app.scss';

	.add-new-btn {
		float: left;
		margin: 0 20px 15px 0;
	}
</style>
