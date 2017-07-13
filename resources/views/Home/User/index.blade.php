@extends('layouts.home')

@section('title','个人中心')

@section('content')
<div class="layout pa-to-10 fo-fa-ar">
    <script src="{{URL::asset('Static/js/slider.js')}}"></script>
    <!-- 个人中心导航栏 -->
    {{--
    <div class="layout ov-hi">
        <div class="breadcrumb-area">
            <foreach name="navigate_user" key="k" item="v">
                <if condition="$k neq '首页'"> > </if>
                <a href="{$v}">{$k}</a>
            </foreach>
        </div>
    </div>
    --}}
    <!--左侧菜单-->
    @include('Home.User.menu')
    <div class="fr wi940">
        <div class="myhome-welcome pa-to-20">
            <div class="fl myhome-tx">
                <div class="w-img">
                    <img src="{{$currUser->head_pic or '/Static/images/img88.jpg'}}" alt="用户头像">
                </div>
                <div class="w-vip po-ab"><i class="w-v0"></i></div>
            </div>
            <div class="ov-hi ma-le-158">
                <h2 class="n-name">{{$currUser->nickname}}，欢迎您！</h2>
                <div class="w-info">
                    <span>我的余额：&nbsp;<em><a href="javascript:;">{{$currUser->user_money}}</a></em></span>
                    <span>等级：{{$level[$currUser->level-1]->level_name}}</span>
                @if($currUser->mobile_validated == 1)
                    <span><a class="link-non-validated" href="javascript:;">已验证手机</a></span>
                @else
                    <span>
                        <a class="link-non-validated2" href="{{url('home/user/validateMobile',['step'=>1])}}">未验证手机</a>
                    </span>
                @endif

                @if($currUser->email_validated == 1)
                    <span><a class="link-non-validated" href="javascript:;">已验证邮箱</a></span>
                @else
                    <span>
                        <a class="link-non-validated2" href="{{url('home/user/validateEmail/step',['step'=>1])}}">未验证邮箱</a>
                    </span>
                @endif
                <!--<span><a class="link-non-validated" href="">未实名认证</a></span>
                    <span>优惠券&nbsp;<em><a href="">{$currUser->coupon_count}</a></em>&nbsp;张</span>
                    -->
                    <!--<span>站内信&nbsp;<em><a href="">0</a></em>&nbsp;张</span>-->
                </div>
                <!--<div class="w-ple pa-to-10">-->
                <!--<dl>-->
                <!--<dt>我的特权：</dt>-->
                <!--<dd><a href=""><img src="__STATIC__/images/icon_privilege_vip_small_light.png"/></a></dd>-->
                <!--<dd><a href=""><img src="__STATIC__/images/icon_privilege_v_small_light.png"/></a></dd>-->
                <!--<dd><a href=""><img src="__STATIC__/images/icon_privilege_yh_small_light.png"/></a></dd>-->
                <!--<dd><a href=""><img src="__STATIC__/images/icon_privilege_vipDay_small_gray.png"/></a></dd>-->
                <!--</dl>-->
                <!--</div>-->
            </div>
            <div class="clickshop">
                <p>-- 欢迎光临TPshop商城 --</p>
                <p style="margin-top:20px"><a href="{{url('/')}}"><span>开始购物</span></a></p>
            </div>
        </div>
    </div>
</div>
<div class="he80"></div>
@stop

@section('javascript')
<script>
    function delimg(file,t){
        $.get(
            "/index.php?m=Admin&c=Uploadify&a=delupload",{action:"del", filename:file},function(){}
        );
        $('#head_pic').val('');
        $('#preview').attr('src','');
        $(t).remove();
    }
    function add_img(str){
        var head_pic = $('#head_pic').val();
        $('#head_pic').val(str);
        $('#preview').attr('src',str);

//        if(!$('#delimg')){
//            $('#img_box').append('<button id="delimg" type="button" onclick="delimg('+"'"+str+"'"+',this)">删除图片</button>');
//        }else{
//            $('#delimg').attr('onclick','delimg('+"'"+str+"'"+',this)');
//        }
        if(!head_pic){
            $('#img_box').append('<button id="delimg" type="button" onclick="delimg('+"'"+str+"'"+',this)">删除图片</button>');
        }else{
            $('#delimg').attr('onclick','delimg('+"'"+str+"'"+',this)');
        }

    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var icon_wh=$(".icon_wh");
        icon_wh.each(function(){
            var thisObj=$(this);
            thisObj.bind("mouseout",function(){
                $(this).next().css("display","none");
            });
            thisObj.bind("mouseover",function(){
                $(this).next().css("display","block");
            });
        });
    });
</script>
@stop