<template>
	<div class="top-nav">
		<div class="top-nav-inner">
			<div class="nav-header">
				<button type="button" class="navbar-toggle pull-left sidebar-toggle" id="sidebarToggleSM">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				
				<a href="index.html" class="brand" style="width: 300px;margin: 0 auto;">
					<i class="fa fa-database"></i><span class="brand-name">克林伯瑞后台</span>
				</a>
			</div>
			<div class="nav-container">
				<button type="button" class="navbar-toggle pull-left sidebar-toggle" id="sidebarToggleLG">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="pull-right m-right-sm">
					<div class="user-block hidden-xs">
						<a href="#" id="userToggle" data-toggle="dropdown">
							<img src="static/img/default-avatar.png" alt="" class="img-circle inline-block user-profile-pic">
							<div class="user-detail inline-block">
								<!-- {{info.Name?info.Name:'暂无'}} -->
								管理员
								<i class="fa fa-angle-down"></i>
							</div>
						</a>
						<div class="panel border dropdown-menu user-panel">
							<div class="panel-body paddingTB-sm">
								<ul>
									<li>
										<a class="C-P">
											<i class="fa fa-power-off fa-lg"></i><span class="m-left-xs">退出登录</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- ./top-nav-inner -->	
	</div>
</template>
<script>
	import { readLocal } from '../../utils/localstorage.js';

	export default {
		name: 'common-header',
		data () {
			return {
				info: {
					Id: '',
					Name: ''
				}
			};
		},
		created () {
			let info = readLocal('user').info;
			if (info) {
				this.info = info;
			}
		}
	};
</script>
<style lang="scss">
</style>
