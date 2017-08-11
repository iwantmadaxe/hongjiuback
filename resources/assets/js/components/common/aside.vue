<template>
	<div class="sidebar-menu fixed">
		<div class="sidebar-inner scrollable-sidebar">
			<div class="main-menu">
				<ul class="accordion">
					<li class="menu-header">
						Main Menu
					</li>
					<li class="bg-palette1">
						<a @click="goProductList">
							<span class="menu-content block">
								<span class="menu-icon"><i class="block fa fa-glass fa-lg"></i></span>
								<span class="text m-left-sm">商品管理</span>
							</span>
							<span class="menu-content-hover block">
								Form
							</span>
						</a>
					</li>
					<li class="bg-palette1">
						<a @click="goMerchantList">
							<span class="menu-content block">
								<span class="menu-icon"><i class="block fa fa-cube fa-lg"></i></span>
								<span class="text m-left-sm">经销商管理</span>
							</span>
							<span class="menu-content-hover block">
								Form
							</span>
						</a>
					</li>
					<li class="openable bg-palette3 left-nav7">
						<a>
							<span class="menu-content block">
								<span class="menu-icon"><i class="block fa fa-file-text fa-lg"></i></span>
								<span class="text m-left-sm">内容发布</span>
							</span>
							<span class="menu-content-hover block">
								Form
							</span>
						</a>
						<ul class="submenu bg-palette4">
							<li><a @click="goBannerList"><span class="submenu-label">banner</span></a></li>
							<li><a @click="goContextList"><span class="submenu-label">大讲堂</span></a></li>
							<li><a @click="goActivityList"><span class="submenu-label">活动</span></a></li>
							<li><a @click="goHistoryList"><span class="submenu-label">历史介绍</span></a></li>
						</ul>
					</li>
					<li class="bg-palette1">
						<a @click="goMsgList">
							<span class="menu-content block">
								<span class="menu-icon"><i class="block fa fa-info fa-lg"></i></span>
								<span class="text m-left-sm">信息管理</span>
							</span>
							<span class="menu-content-hover block">
								Form
							</span>
						</a>
					</li>
					<li class="bg-palette1">
						<a @click="goManpowerList">
							<span class="menu-content block">
								<span class="menu-icon"><i class="block fa fa-user-o fa-lg"></i></span>
								<span class="text m-left-sm">人员管理</span>
							</span>
							<span class="menu-content-hover block">
								Form
							</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'common-aside',
		data () {
			return {
			};
		},
		methods: {
			goProductList () {
				this.$router.push({name: 'ProductList'});
			},
			goMerchantList () {
				this.$router.push({name: 'MerchantList'});
			},
			goBannerList () {
				this.$router.push({name: 'BannerList'});
			},
			goContextList () {
				this.$router.push({name: 'ContextList'});
			},
			goActivityList () {
				this.$router.push({name: 'ActivityList'});
			},
			goHistoryList () {
				this.$router.push({name: 'HistoryList'});
			},
			goMsgList () {
				this.$router.push({name: 'MsgList'});
			},
			goManpowerList () {
				this.$router.push({name: 'ManList'});
			}
		}
	};
</script>
<style lang="scss">
</style>
