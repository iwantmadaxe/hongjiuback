@extends('admin.app')
@section('content')
    <div class="padding-md">
        <div class="row">
            <div class="col-sm-6">
                <div class="page-title">
                    主页
                </div>
                <div class="page-sub-header">
                    Welcome Back, 
                    {{ \Auth::guard('admin')->user()->username }}
                </div>
            </div>
        </div>

        <!--数据块-->
        <div class="row m-top-md">
            <div class="col-lg-3 col-sm-6">
                <div class="statistic-box bg-purple m-bottom-md">
                    <div class="statistic-title">
                        当前答疑解惑数
                    </div>

                    <div class="statistic-value">
                        123
                    </div>

                    <div class="m-top-md">&nbsp;</div>

                    <div class="statistic-icon-background">
                        <i class="ion-person-add"></i>
                    </div>
                </div>
            </div>
        </div>
        <!--end 数据块-->
    </div><!-- ./padding-md -->
@endsection