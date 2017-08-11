@extends('admin.app')
@section('content')
    <div class="padding-md">
        <dx-header name="信息新增"></dx-header>
        <!--数据块-->
        <div class="row m-top-md">
            <msg-add></msg-add>
        </div>
        <!--end 数据块-->
    </div><!-- ./padding-md -->
@endsection

@section('moreScript')
<script>
    $(function () {
        $(".accordion li").removeClass("active");
        $(".accordion .left-nav4").addClass("open");
        $(".accordion .left-nav4").find("ul").show();
        $(".accordion .left-nav4").find("li").eq(0).addClass("active");
    })
</script>
@endsection