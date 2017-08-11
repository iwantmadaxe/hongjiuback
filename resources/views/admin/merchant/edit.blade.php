@extends('admin.app')
@section('content')
    <div class="padding-md">
        <dx-header name="经销商编辑"></dx-header>
        <!--数据块-->
        <div class="row m-top-md">
            <merchant-edit></merchant-edit>
        </div>
        <!--end 数据块-->
    </div><!-- ./padding-md -->
@endsection

@section('moreScript')
<script>
    $(function () {
        $(".accordion li").removeClass("active");
        $(".accordion .left-nav2").addClass("open");
        $(".accordion .left-nav2").find("ul").show();
        $(".accordion .left-nav2").find("li").eq(0).addClass("active");
    })
</script>
@endsection