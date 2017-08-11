<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>电信卡</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="电信卡">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="apple-touch-icon-precomposed" href="{{asset('images/logo/114.png')}}" sizes="114x114"/>
    <link rel="shortcut icon" href="{{asset('images/logo/32.ico')}}" type="image/x-icon"/>
    <meta name="author" content="Keal">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link href="//cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- ionicons -->
    <link href="//cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">

    <!-- Animate -->
    <link href="//cdn.bootcss.com/animate.css/3.5.1/animate.min.css" rel="stylesheet">

    <!-- Simplify -->
    <link href="{{asset('css/admin/simplify.min.css')}}" rel="stylesheet">
    {{--<link href="http://7xqxb2.com1.z0.glb.clouddn.com/simplify.min.css" rel="stylesheet">--}}

    <!-- 引入element样式 -->
    <link href="https://cdn.bootcss.com/element-ui/1.2.8/theme-default/index.css" rel="stylesheet">

    <!-- app.css -->
    <link href="{{url(mix("css/app.css"))}}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    @yield('moreCss')
</head>

<body class="overflow-hidden">
<div class="wrapper preload">
    <header class="top-nav" id="top-news">
        <div class="top-nav-inner">
            <div class="nav-header">
                <button type="button" class="navbar-toggle pull-left sidebar-toggle" id="sidebarToggleSM">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <ul class="nav-notification pull-right">
                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-lg"></i></a>
                        <span class="badge badge-danger bounceIn">10</span>
                        <ul class="dropdown-menu dropdown-sm pull-right user-dropdown">
                            <li class="user-avatar">
                                <img src="#" alt="图片" class="img-circle">
                                <div class="user-content">
                                    <h5 class="no-m-bottom">{{ \Auth::guard('admin')->user()->username }}</h5>
                                    <div class="m-top-xs">
                                        {{--<a href="#" class="m-right-sm" data-toggle="modal" data-target="#completeDetail">个人信息</a>--}}
                                        <a  href="{{ url('admin/logout') }}" class="C-P">退出</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="#" data-toggle="modal" data-target="#resetOwnPwd">
                                    修改密码
                                    {{--<span class="badge badge-danger bounceIn animation-delay2 pull-right">1</span>--}}
                                </a>
                            </li>
                            {{--<li>--}}
                                {{--<a href="{{ url('admin/news') }}">--}}
                                    {{--提醒--}}
                                    {{--<span class="badge badge-purple bounceIn animation-delay1 pull-right" v-show="total" v-cloak>@{{ total }}</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);">设置</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <a href="{{url('admin/product/list')}}" class="brand">
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
                            <img src="{{url('/images/logo/default-avatar.png')}}" alt="poster"
                                 class="img-circle inline-block user-profile-pic">

                            <div class="user-detail inline-block">
                                {{ \Auth::guard('admin')->user()->username }}
                                <i class="fa fa-angle-down"></i>
                            </div>
                        </a>

                        <div class="panel border dropdown-menu user-panel">
                            <div class="panel-body paddingTB-sm">
                                <ul>
<!--                                     <li>
                                        <a href="#" data-toggle="modal" data-target="#completeDetail">
                                            <i class="fa fa-edit fa-lg"></i><span class="m-left-xs">个人信息</span>
                                        </a>
                                    </li> -->
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#resetOwnPwd">
                                            <i class="fa fa-inbox fa-lg"></i><span class="m-left-xs">修改密码</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a  href="{{ url('admin/logout') }}" class="C-P">
                                            <i class="fa fa-power-off fa-lg"></i><span class="m-left-xs">退出</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- ./top-nav-inner -->
    </header>
    <aside class="sidebar-menu fixed">
        <div class="sidebar-inner scrollable-sidebar">
            <div class="main-menu">
                <ul class="accordion">
                    <li class="bg-palette1 left-nav1">
                        <a href="{{ url('/admin/product/list') }}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-glass fa-lg"></i></span>
                                <span class="text m-left-sm">商品管理</span>
                            </span>
                            <span class="menu-content-hover block">
                                Form
                            </span>
                        </a>
                    </li>
                    <li class="bg-palette2 left-nav2">
                        <a href="{{ url('/admin/merchant/list') }}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-cube fa-lg"></i></span>
                                <span class="text m-left-sm">经销商管理</span>
                            </span>
                            <span class="menu-content-hover block">
                                Form
                            </span>
                        </a>
                    </li>
                    <li class="openable bg-palette3 left-nav3">
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
                            <li><a  href="{{ url('/admin/banner/list') }}"><span class="submenu-label">banner</span></a></li>
                            <li><a href="{{ url('/admin/context/list') }}"><span class="submenu-label">大讲堂</span></a></li>
                            <li><a href="{{ url('/admin/activity/list') }}"><span class="submenu-label">活动</span></a></li>
                            <li><a href="{{ url('/admin/history/list') }}"><span class="submenu-label">历史介绍</span></a></li>
                        </ul>
                    </li>
                    <li class="bg-palette1 left-nav4">
                        <a href="{{ url('/admin/msg/list') }}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-info fa-lg"></i></span>
                                <span class="text m-left-sm">信息管理</span>
                            </span>
                            <span class="menu-content-hover block">
                                Form
                            </span>
                        </a>
                    </li>
                    <li class="bg-palette1 left-nav5">
                        <a href="{{ url('/admin/man/list') }}">
                            <span class="menu-content block">
                                <span class="menu-icon"><i class="block fa fa-user fa-lg"></i></span>
                                <span class="text m-left-sm">人员管理</span>
                            </span>
                            <span class="menu-content-hover block">
                                Form
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="sidebar-fix-bottom clearfix">
                <a  href="{{ url('admin/logout') }}" class="pull-right font-18 C-P"><i class="ion-log-out" title="退出"></i></a>
            </div>
        </div><!-- sidebar-inner -->
    </aside>

    <div id="app" class="main-container">
        @yield('content')
    </div><!-- /main-container -->

    <footer class="footer">
        <span class="footer-brand">
            <strong class="text-danger">克林伯瑞后台</strong> For Odin
        </span>

        <p class="no-margin">
            &copy; 2016-{{ \Carbon\Carbon::now()->year }} <strong>Odin</strong>. ALL Rights Reserved.
        </p>
    </footer>
</div><!-- /wrapper -->

@yield('addition')
<a href="#" class="scroll-to-top hidden-print"><i class="fa fa-chevron-up fa-lg"></i></a>

<div class="nav-ctrl-cover" id="managers">
    {{--complete-detail--}}
    <div class="modal fade" id="completeDetail" tabindex="-1" role="dialog" aria-labelledby="completeDetail" v-cloak>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="completeDetailLabel">个人资料</h4>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <h3 class="inline-block">欢迎您！</h3>
                                <span class="user-name">@{{ managerInfo['name'] }}</span>,
                                <span class="role-name">@{{ managerInfo['role'] }}</span>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <form class="form-horizontal">
                                    <div class="form-group" :class="{'has-error':managerErrors.phone.isInvalid}">
                                        <label for="phone" class="col-sm-3 control-label">电话:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="phone" v-model="managerInfo['phone']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" :style="{'display':managerErrors.phone.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.phone.validInfo }}</p>
                                    </div>

                                    <div class="form-group" :class="{'has-error':managerErrors.email.isInvalid}">
                                        <label for="email" class="col-sm-3 control-label">邮箱:</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" id="email" v-model="managerInfo['email']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" style="{padding-left: 15px;}" :style="{'display':managerErrors.email.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.email.validInfo }}</p>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <p class="text-danger">请完善个人信息，以便让用户能及时与您联系</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="text-align: center;">
                    <button type="button" class="btn btn-primary inline-block" v-disabled="managerInfoCompleted" style="width: 50%;" @click="updateInfo">确认修改</button>
                </div>
            </div>
        </div>
    </div>

    {{--reset-own-pwd--}}
    <div class="modal fade" id="resetOwnPwd" tabindex="-1" role="dialog" aria-labelledby="resetOwnPwd" v-cloak>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="completeDetailLabel">修改密码</h4>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <form class="form-horizontal">
                                    <div class="form-group" :class="{'has-error':managerErrors.oldPwd.isInvalid}">
                                        <label for="old_pwd" class="col-sm-3 control-label">原密码:</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="old_pwd" v-model="pwdInfo['oldPwd']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" :style="{'display':managerErrors.oldPwd.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.oldPwd.validInfo }}</p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-2 text-center">
                                            <p class="text-danger">密码由6-16位字母，数字组成，区分大小写</p>
                                        </div>
                                    </div>

                                    <div class="form-group" :class="{'has-error':managerErrors.pwd.isInvalid}">
                                        <label for="pwd" class="col-sm-3 control-label">新密码:</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="pwd" v-model="pwdInfo['pwd']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" style="{padding-left: 15px;}" :style="{'display':managerErrors.pwd.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.pwd.validInfo }}</p>
                                    </div>

                                    <div class="form-group" :class="{'has-error':managerErrors.pwdConfirmation.isInvalid}">
                                        <label for="pwd_confirmation" class="col-sm-3 control-label">确认密码:</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="pwd_confirmation" v-model="pwdInfo['pwdConfirmation']">
                                        </div>
                                        <div class="clearfix"></div>
                                        <p class="help-block col-sm-offset-3 text-left" style="{padding-left: 15px;}" :style="{'display':managerErrors.pwdConfirmation.isInvalid?'block':'none','padding-left':'15px'}">@{{ managerErrors.pwdConfirmation.validInfo }}</p>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer" style="text-align: center;">
                    <button type="button" class="btn btn-primary inline-block" v-disabled="pwdInfoCompleted" style="width: 50%;" @click="resetOwnPwd">确认修改</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<!-- Jquery -->
<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- app.js -->
<script src="{{url(mix("js/app.js"))}}"></script>

<!-- Slimscroll -->
<script src="//cdn.bootcss.com/jQuery-slimScroll/1.3.7/jquery.slimscroll.min.js"></script>

<!-- Simplify -->
<script src="{{asset('js/admin/simplify.js')}}"></script>
<script src="{{asset('js/admin/simplify_dashboard.js')}}"></script>

@yield('moreScript')

{{-- 修改个人信息 --}}
<script>
    //绑定按钮锁定
    Vue.directive('disabled', {
        bind: function (el, binding) {
            if (!binding.value){
                el.setAttribute('disabled', 'disabled');
            }else{
                el.removeAttribute('disabled');
            }
        },
        update: function (el, binding) {
            // 值更新时的工作
            // 也会以初始值为参数调用一次
            if (!binding.value){
                el.setAttribute('disabled', 'disabled');
            }else{
                el.removeAttribute('disabled');
            }
        }
    });

    new Vue({
        el: '#managers',
        data: {
            managerInfo: {
                id: '{{ \Auth::guard('admin')->user()->id }}',
                name: '{{ \Auth::guard('admin')->user()->username }}',
                email: '{{ \Auth::guard('admin')->user()->email }}',
                phone: '{{ \Auth::guard('admin')->user()->phone }}',
                role: '',
                is_first: '{{ \Auth::guard('admin')->user()->is_first }}',
            },
            pwdInfo: {
                oldPwd: '',
                pwd: '',
                pwdConfirmation: ''
            },
            managerErrors: {
                phone: {
                    isInvalid: 0,
                    validInfo: ''
                },
                email: {
                    isInvalid: 0,
                    validInfo: ''
                },
                oldPwd: {
                    isInvalid: 0,
                    validInfo: ''
                },
                pwd: {
                    isInvalid: 0,
                    validInfo: ''
                },
                pwdConfirmation: {
                    isInvalid: 0,
                    validInfo: ''
                }
            }
        },
        computed: {
            managerInfoCompleted: function () {
                if (this.managerInfo.phone && this.managerInfo.email) {
                    return 1;
                }else {
                    return 0;
                }
            },
            pwdInfoCompleted: function () {
                if (this.pwdInfo.oldPwd && this.pwdInfo.pwd && this.pwdInfo.pwdConfirmation && this.pwdInfo.pwd===this.pwdInfo.pwdConfirmation) {
                    return 1;
                }else {
                    return 0;
                }
            }
        },
        mounted: function () {
            if (this.managerInfo.is_first === '0'){
                $("#completeDetail").modal("show");
            }else{
                $("#completeDetail").modal("hide");
            }
        },
        methods: {
            updateInfo: function () {
                var _this=this;
                _this.managerErrors.phone.isInvalid = 0;
                _this.managerErrors.email.isInvalid = 0;
                _this.managerErrors.phone.validInfo = '';
                _this.managerErrors.email.validInfo = '';
                if(!_this.managerInfo.phone || _this.managerInfo.phone==''){
                    _this.managerErrors.phone.validInfo = '联系电话必填！';
                    _this.managerErrors.phone.isInvalid = 1;
                    return false;
                }

                if(_this.managerInfo.phone.length<6){
                    _this.managerErrors.phone.validInfo = '电话位数错误！';
                    _this.managerErrors.phone.isInvalid = 1;
                    return false;
                }

                if (!_this.managerInfo.phone.match(/^\d{0,4}-?\d{7,8}#?\d{0,4}$/)){
                    _this.managerErrors.phone.validInfo = '电话格式错误！';
                    _this.managerErrors.phone.isInvalid = 1;
                    return false;
                }

                if(!_this.managerInfo.email.match(/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/)){
                    _this.managerErrors.email.validInfo = '登录账户必为邮箱！';
                    _this.managerErrors.email.isInvalid = 1;
                    return false;
                }
                var url = "{{ url('admin/manager') }}"+"/"+_this.managerInfo.id+"?user_id="+_this.managerInfo.id;
                $.ajax({
                    url:url,
                    dataType:'json',
                    headers:{
                        'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                    },
                    timeout:60000,
                    data: {
                        email: _this.managerInfo.email,
                        phone: _this.managerInfo.phone,
                        _method:'PUT'
                    },
                    type:'POST'
                }).done(function (data) {
                    if(data.ret_num === 0){
                        $("#completeDetail").modal("hide");
                        if (data.reLogin == 1){
                            alert("保存成功！请重新登录！");
                            window.location.href = data.reUrl;
                        }else{
                            alert(data.ret_msg);
                        }
                    }else{
                        _this.managerErrors.phone.validInfo = data.ret_msg;
                        _this.managerErrors.phone.isInvalid = 1;
                    }
                }).fail(function (data) {
                    var errs=JSON.parse(data.responseText);
                    if(errs.email){
                        _this.managerErrors.email.isInvalid = 1;
                        _this.managerErrors.email.validInfo = errs.email[0];
                    }
                    if(errs.phone){
                        _this.managerErrors.phone.isInvalid = 1;
                        _this.managerErrors.phone.validInfo = errs.phone[0];
                    }
                });
            },
            resetOwnPwd: function () {
                var _this=this;
                _this.managerErrors.oldPwd.isInvalid = 0;
                _this.managerErrors.pwd.isInvalid = 0;
                _this.managerErrors.pwdConfirmation.isInvalid = 0;
                _this.managerErrors.oldPwd.validInfo = '';
                _this.managerErrors.pwd.validInfo = '';
                _this.managerErrors.pwdConfirmation.validInfo = '';
                if(!_this.pwdInfo.oldPwd){
                    _this.managerErrors.oldPwd.validInfo = '旧密码必填！';
                    _this.managerErrors.oldPwd.isInvalid = 1;
                    return false;
                }

                if (!_this.pwdInfo.pwd){
                    _this.managerErrors.pwd.validInfo = '新密码必填！';
                    _this.managerErrors.pwd.isInvalid = 1;
                    return false;
                }

                if(_this.pwdInfo.oldPwd === _this.pwdInfo.pwd){
                    _this.managerErrors.pwd.validInfo = '新密码与旧密码一致！';
                    _this.managerErrors.pwd.isInvalid = 1;
                    return false;
                }

                if(_this.pwdInfo.pwd.length<6){
                    _this.managerErrors.pwd.validInfo = '新密码至少6位！';
                    _this.managerErrors.pwd.isInvalid = 1;
                    return false;
                }

                if(!_this.pwdInfo.pwd.match(/=|\+|-|@|_|\*|[a-zA-Z]/g)){
                    _this.managerErrors.pwd.validInfo = '"A-Z" "a-z" "+" "_" "*" "=" "-" "@"至少存在1项！';
                    _this.managerErrors.pwd.isInvalid = 1;
                    return false;
                }

                if (_this.pwdInfo.pwd !== _this.pwdInfo.pwdConfirmation){
                    _this.managerErrors.pwdConfirmation.validInfo = '两次密码不一致！';
                    _this.managerErrors.pwdConfirmation.isInvalid = 1;
                    return false;
                }

                var url = "{{ url('admin/account') }}"+"/"+_this.managerInfo.id;
                $.ajax({
                    url:url,
                    dataType:'json',
                    headers:{
                        'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                    },
                    timeout:60000,
                    data: {
                        old_pwd: _this.pwdInfo.oldPwd,
                        pwd: _this.pwdInfo.pwd,
                        pwd_confirmation: _this.pwdInfo.pwdConfirmation,
                        _method:'PUT'
                    },
                    type:'POST'
                }).done(function (data) {
                    if(data.ret_num === 0){
                        $("#completeDetail").modal("hide");
                        if (data.reLogin == 1){
                            alert("保存成功！请重新登录！");
                            window.location.href = data.reUrl;
                        }else{
                            alert(data.ret_msg);
                        }
                    }else{
                        _this.managerErrors.oldPwd.validInfo = data.ret_msg;
                        _this.managerErrors.oldPwd.isInvalid = 1;
                    }
                }).fail(function (data) {
                    var errs = JSON.parse(data.responseText);
                    if (errs.old_pwd){
                        _this.managerErrors.oldPwd.isInvalid = 1;
                        _this.managerErrors.oldPwd.validInfo = errs.old_pwd[0];
                    }
                    if (errs.pwd){
                        _this.managerErrors.pwd.isInvalid = 1;
                        _this.managerErrors.pwd.validInfo = errs.pwd[0];
                    }
                    if (errs.pwd_confirmation){
                        _this.managerErrors.pwdConfirmation.isInvalid = 1;
                        _this.managerErrors.pwdConfirmation.validInfo = errs.pwd_confirmation[0];
                    }
                });
            }
        }
    });
</script>
<script>
    new Vue({
        el: '#top-news',
        data: {
            total: 0,
            news: []
        },
        mounted: function () {
            var url = "{{ url('api/v1/admin/notify') }}";
            var _this = this;
            $.ajax({
                url:url,
                dataType:'json',
                headers:{
                    'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                },
                timeout:60000,
                data: {},
                type:'GET'
            }).done(function (data) {
                _this.total = data.total;
                _this.news = data.data;
            }).fail(function (data) {
                alert("网络错误！");
                return false;
            });
        },
        methods: {
            logout: function () {
                var url = 'http://telecom.odinsoft.com.cn/api/v1/user/logout';
                $.ajax({
                    url:url,
                    dataType:'json',
                    headers:{
                        'X-CSRF-TOKEN':$("meta[name=csrf-token]").attr('content'),
                    },
                    timeout:60000,
                    type:'get'
                }).done(function (data) {
                    window.location.href = '/';
                }).fail(function (data) {
                });
            }
        }
    });
</script>

</body>
<style type="text/css">
    .C-P {
        cursor: pointer;
    }
</style>
</html>
