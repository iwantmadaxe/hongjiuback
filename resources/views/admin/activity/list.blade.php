@extends('admin.app')
@section('content')
    <div class="padding-md">
        <dx-header name="活动列表"></dx-header>
        <!--数据块-->
        <div class="row m-top-md">
            <activity-list></activity-list>
        </div>
        <!--end 数据块-->
    </div><!-- ./padding-md -->
@endsection

@section('moreScript')
<script>
    $(function () {
        $(".accordion li").removeClass("active");
        $(".accordion .left-nav3").addClass("open");
        $(".accordion .left-nav3").find("ul").show();
        $(".accordion .left-nav3").find("li").eq(0).addClass("active");
    })
</script>
@endsection