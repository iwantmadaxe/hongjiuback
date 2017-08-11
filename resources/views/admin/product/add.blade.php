@extends('admin.app')
@section('content')
    <div class="padding-md">
        <dx-header name="商品新增"></dx-header>
        <!--数据块-->
        <div class="row m-top-md">
            <product-add></product-add>
        </div>
        <!--end 数据块-->
    </div><!-- ./padding-md -->
@endsection

@section('moreScript')
<script>
    $(function () {
        $(".accordion li").removeClass("active");
        $(".accordion .left-nav1").addClass("open");
        $(".accordion .left-nav1").find("ul").show();
        $(".accordion .left-nav1").find("li").eq(0).addClass("active");
    })
</script>
@endsection