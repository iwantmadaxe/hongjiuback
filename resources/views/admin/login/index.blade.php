<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>电信卡管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="电信卡管理">
    <meta name="author" content="keal">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link href="//cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- ionicons -->
    <link href="//cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">

    <!-- Simplify -->
    <link href="{{asset('css/admin/simplify.min.css')}}" rel="stylesheet">

    <link href="{{asset('css/admin/login.min.css')}}" rel="stylesheet">
</head>

<body class="overflow-hidden light-background">

<div class="cotn_principal">
    <div class="cont_centrar">
        <div class="cont_login">
            <div class="cont_info_log_sign_up">
                <div class="col_md_login">
                    <div class="cont_ba_opcitiy">
                        <h2>登 录</h2>
                        <p>电信卡管理系统</p>
                        <button class="btn_login" onClick="cambiar_login()" style="outline: none;">去登录<span class="animated-go">&nbsp; > > ></span></button>
                    </div>
                </div>
            </div>
            <div class="cont_back_info">
                <div class="cont_img_back_grey"> <img src="{{asset('images/login/po.jpg')}}" alt="" /> </div>
            </div>
            <div class="cont_forms">
                <div class="cont_img_back_"> <img src="{{asset('images/login/po.jpg')}}" alt="" /> </div>
                <form id="login" :class="[error.password.isErr || error.username.isErr ? 'has-error':'']">
                    <div class="cont_form_login">
                        <a href="javascript:void(0)" onClick="ocultar_login_sign_up()">
                            <i class="fa fa-arrow-left"></i>
                        </a>

                        <h2>登 录</h2>

                        <input type="text" name="username" placeholder="账号" v-model="auth.username">
                        <span class="help-block" v-if="error.username.isErr">
                            <strong>@{{ error.username.msg }}</strong>
                        </span>

                        <input type="password" name="password" placeholder="密码" v-model="auth.password">
                        <span class="help-block" v-if="error.password.isErr">
                            <strong>@{{ error.password.msg }}</strong>
                        </span>

                        <button type="submit" class="btn_login" @click.prevent="login" v-if="lock==0">登 录</button>
                        <button class="btn_login" v-if="lock==1" disabled>登 录 中</button>
                        <button class="btn_login" v-if="lock==2">登 录 成 功</button>
                    </div>
                </form>
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
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- Vue -->
<script src="//cdn.bootcss.com/vue/2.2.6/vue.min.js"></script>

<!-- Slimscroll -->
<script src="//cdn.bootcss.com/jQuery-slimScroll/1.3.7/jquery.slimscroll.min.js"></script>

<!-- Simplify -->
<script src="{{asset('js/admin/simplify.js')}}"></script>
<script src="{{asset('js/admin/simplify_dashboard.js')}}"></script>

<script src="{{asset('js/admin/login.min.js')}}"></script>
<script>
    new Vue({
        el: '#login',
        data: {
            auth: {
                username: '',
                password: '',
                _token: "{{ csrf_token() }}"
            },
            error: {
                username: {
                    isErr: false,
                    msg: ''
                },
                password: {
                    isErr: false,
                    msg: ''
                }
            },
            lock: 0
        },
        methods: {
            login: function () {
                var _this = this;
                var url = "{{ url('admin/login') }}";
                this.auth.username = $.trim(this.auth.username);
                this.error = {
                    username: {
                        isErr: false,
                        msg: ''
                    },
                    password: {
                        isErr: false,
                        msg: ''
                    }
                };
                if (!this.auth.username) {
                    this.error.username.isErr = true;
                    this.error.username.msg = '账号必填！';
                    return false;
                }
                if (!this.auth.password) {
                    this.error.password.isErr = true;
                    this.error.password.msg = '密码必填！';
                    return false;
                }
                this.lock = 1;
                $.ajax({
                    url: url,
                    data: _this.auth,
                    type: 'POST',
                    success: function (res) {
                        _this.lock = 2;
                        window.location.href = "{{ url('admin/home') }}";
                    },
                    error: function (err) {
                        if (err.responseJSON.status_code == 400) {
                            _this.error.password.isErr = true;
                            _this.error.password.msg = err.responseJSON.message;
                        } else {
                            _this.error.password.isErr = true;
                            _this.error.password.msg = "系统错误，请稍后重试！";
                        }
                        _this.lock = 0;
                    },
                    dataType: 'json'
                });
            }
        }
    });
</script>
</body>
</html>
