<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>优惠券-{$tpshop_config['shop_info_store_title']}</title>
    <meta http-equiv="keywords" content="{$tpshop_config['shop_info_store_keyword']}" />
    <meta name="description" content="{$tpshop_config['shop_info_store_desc']}" />    
    <link rel="stylesheet" href="{{URL::asset('Static/css/index.css')}}" type="text/css">
    <link rel="stylesheet" href="{{URL::asset('Static/css/page.css')}}" type="text/css">
    <script src="{{URL::asset('Static/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{URL::asset('Static/js/slider.js')}}"></script>
</head>

<body>
<!--------头部开始-------------->
{{--<include file="Public/header" />--}}
@include('Home.Public.header')
<!--------头部结束-------------->

<div class="layout ov-hi">
    <div class="breadcrumb-area">    
	    <foreach name="navigate_user" key="k" item="v">
	        <if condition="$k neq '首页'"> > </if>  
            <a href="{$v}">{$k}</a> 
        </foreach>
    </div>
</div>
<div class="layout pa-to-10 fo-fa-ar">
    <!--菜单-->
    {{--<include file="User/menu" />--}}
    @include('Home.User.menu')
    <!--菜单-->
    <div class="fr wi940">
        <div class="he50 wddd">
            <div class="fl ddd-h2">
                <h2><span>我的优惠券</span></h2>
            </div>
            <div class="fr">
                <div class="po-re zjs">
                    <ul>
                        <li id="q-s" class="fl cullent"><a href="{{url('home/user/coupom')}}"><span>未使用</span></a></li>
                        <li id="h-s" class="fl"><a href="{:U('Home/User/coupon',array('type'=>1))}"><span>已使用</span></a></li>
                        <li id="j-s" class="fl"><a href="{:U('Home/User/coupon',array('type'=>2))}"><span>已过期</span></a></li>
                    </ul>
                    <div class="ec-ta-x wi68" style="left:0"></div>
                </div>
            </div>
        </div>
        <div class="wddd-js ov-in">
            <div class="list-group-title">
                <table class="merge-tab" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="col-pro wi197">优惠券类型</th>
                        <th class="wi150">优惠券编号</th>
                        <th class="col-quty wi138">优惠金额</th>
                        <th class="col-pay wi252">有效期</th>
                        <th class="col-operate">使用要求(订单金额)</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="merge-list">
 <if condition="empty($coupon_list)"><!--没查询到数据-->
     <p style="text-align:center"><img src="{{URL::asset('Static/images/null_data.jpg')}}" width="400"  /></p>
 </if>            
                <div class="ma-0--1">
                    <div class="o-pro">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                            <volist name="coupon_list" id="coupon">
                                <tr>
                                    <td class="col-pro-info te-al wi197"><p>{$coupon.name}</p></td>
                                    <td class="wi150"><span>{$coupon.code|default='N'}</span></td>
                                    <td class="col-quty wi138">{$coupon.money}</td>
                                    <td rowspan="1" class="col-pay wi252"><span>{$coupon.use_end_time|date='Y-m-d H:s',###}</span></td>
                                    <td rowspan="1" class="col-operate">
                                        <p class="p-link">{$coupon.condition}</p>
                                    </td>
                                </tr>
                            </volist>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div>{$page}</div>
    </div>
</div>
<div class="he80"></div>
<!--------footer-开始-------------->
{{--<include file="Public/footer2" />--}}
@include('Home.Public.footer2')
<!--------footer-结束-------------->


</body>
<script>
    /*$(function () {
     $("#h-s").click(function () {
     $('.ec-ta-x').css('left','124px');
     $('.ec-ta-x').css('width','110px');
     $(this).addClass("cullent");
     $('#q-s').removeClass("cullent");
     });
     });
     */
</script>

<script>
    $(function () {
        $(function () {
            $("#q-s").mouseover(function () {
                $('.ec-ta-x').css('left','0px');
                $(this).addClass("cullent");
            });
            $("#q-s").mouseout(function () {
                $('.ec-ta-x').css('left','0px');
            });
        });
        $("#h-s").mouseover(function () {
            $('.ec-ta-x').css('left','68px');
            $(this).addClass("cullent");
        });
        $("#h-s").mouseout(function () {
            $('.ec-ta-x').css('left','0px');
            $(this).removeClass("cullent");
        });
    });

    $(function () {
        $("#j-s").mouseover(function () {
            $('.ec-ta-x').css('left','136px');
            $(this).addClass("cullent");
        });
        $("#j-s").mouseout(function () {
            $('.ec-ta-x').css('left','0px');
            $(this).removeClass("cullent");
        });
    });
</script>
</html>>>>