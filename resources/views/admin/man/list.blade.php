@extends('admin.app')
@section('content')
    <div class="padding-md">
        <dx-header name="人员列表"></dx-header>
        <!--数据块-->
        <div class="row m-top-md">
            <man-list></man-list>
        </div>
        <!--end 数据块-->
    </div><!-- ./padding-md -->
@endsection

@section('moreScript')
<script>
    $(function () {
        $(".accordion li").removeClass("active");
        $(".accordion .left-nav5").addClass("open");
        $(".accordion .left-nav5").find("ul").show();
        $(".accordion .left-nav5").find("li").eq(0).addClass("active");
    })
</script>
@endsection