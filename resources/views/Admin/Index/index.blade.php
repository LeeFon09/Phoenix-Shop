{{--<include file="Public/header" />--}}
@include('Admin.Public.header')

{{--<include file="Public:left" />--}}
@include('Admin.Public.left')
<section class="content-wrapper right-side" id="riframe" style="margin:0px;padding:0px;margin-left:230px;">
    <iframe id='rightContent' name='rightContent' src="{{url('admin/welcome')}}" width='100%' frameborder="0"></iframe>
    {{--{:U('Admin/Index/welcome')}--}}
</section>
{{--<include file="Public:footer" />--}}
@include('Admin.Public.footer')
