@extends('layouts.home')

@section('title','我的订单')

@section('content')
<link rel="stylesheet" href="{{URL::asset('Static/css/index.css')}}" type="text/css">
<link rel="stylesheet" href="{{URL::asset('Static/css/page.css')}}" type="text/css">
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
    @include('Home.User.menu')
    <!--菜单-->
    <div class="fr wi940">
        <div class="he50 wddd">
            <div class="fl ddd-h2">
                <h2><span>我的订单</span></h2>
            </div>

        </div>
        <div class="wddd-li">
            <ul>
                <li class="wddd-red" id="ALL"><a href="{{url('home/order/orderList')}}">全部</a></li>
                <li id="WAITPAY"><a href="{{url('home/order/orderList/1')}}">待付款<em></em></a></li>
                <li id="WAITSEND"><a href="{{url('home/order/orderList/3')}}">待发货<em></em></a></li>
                <li id="WAITRECEIVE"><a href="{{url('home/order/orderList/5')}}">待收货<em></em></a></li>
                <li id="WAITCCOMMENT"><a href="{{url('home/order/orderList/7')}}">待评价<em></em></a></li>
            </ul>
        </div>
        <div class="wddd-js ov-in">
            <!--<div class="merge">-->
                <!--<label class="ma-ri-20 di-in-bl cu-po" for="checkallbox"><input class="ma-ri-10  ve-al-mi" id="checkallbox" type="checkbox"><span class="ve-al-mi fo-si-14 fo-fa-ta">全选</span></label>-->
                <!--<a class=" di-in-bl hb-merge" href="">合并支付</a>-->
            <!--</div>-->
            <div class="list-group-title">
                <table class="merge-tab" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                    	<th class="col-pro-img wi120 borsdjk"></th>
                        <th class="col-pro">商品</th>
                        <th class="col-price">单价/元</th>
                        <th class="col-price">会员价</th>                        
                        <th class="col-quty">数量</th>
                        <th class="col-pay wi139">实付款/元</th>
                        <th class="col-operate">订单状态及操作</th>
                    </tr>
                    </thead>
                </table>
            </div>

            @if(empty($orderList))
                <p style="text-align:center"><img src="{{URL::asset('Static/images/null_data.jpg')}}" width="400"  /></p>
            @endif
            <div class="merge-list">
                <!--订单列表-->
                @foreach($orderList as $list)
                <div class="ma-0--1">
                    <div class="o-info o-inff">
                        <div class="fl">
                            <input class="o-ch ve-al-mi" type="checkbox" />
                            <span class=" ma-ri-15 co-888 fo-si-14">{{$list->commit_time}}</span>
                            <span class="ma-ri-15 co-888 fo-si-14">订单号：
                                <a class="co-36c" href="{{url('home/order/orderDetail',['oid'=>$list->order_id])}}">{{$list->order_sn}}</a></span>
                        </div>
                        <div class="fr te-al co-888 fo-si-14">{{$status[$list->order_status]}}</div>
                    </div>
                    <div class="o-pro">
                        <table border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        @foreach($list->details as $goods)
                        <tr>
                            <td class="col-pro-img">
                                <p>
                                <a title="{{$goods->goods_name}}" href="{{url('home/goods/goodsinfo',['id'=>$goods->goods_id])}}" target="_blank">
                                    <img src="{{$goods->spec_image_src or $goods->original_img }}" style="width: 78px;height: 78px;">
                                </a>
                                </p>
                            </td>
                            <td class="col-pro-info te-al-le" ><p class="p-name">
                                <a title="" target="_blank" href="{{url('home/goods/goodsinfo',['id'=>$goods->goods_id])}}">{{$goods->goods_name}}
                                </a></p>
                                <p class="p-name">{{$goods->spec_key_name}}</p>
                            </td>
                            <td class="col-price"><em>¥</em><span>{{$goods->goods_price}}</span></td>
                            <td class="col-price"><em>¥</em><span>{{$goods->benefit_price}}</span></td>
                            <td class="col-quty">{{$goods->goods_num}}</td>
                            <td rowspan="1" class="col-pay"><p><em>¥</em><span>{{$goods->total_price}}</span></p></td>
                            <td rowspan="1" class="col-pay">
                            @if(in_array($list->order_status,[3,5]))
                                <p class="p-link">
                                    <a  href="{:U('Home/User/return_goods')}"><span>申请退款</span></a>
                                </p>
                            @endif
                            @if($list->order_status == 7)
                                <p class="p-link">
                                    <a href="{{url('home/user/returnGoodsAdd/gid/'.$goods->goods_id.'?orderid='.$list->order_id.'&ordersn='.$list->order_sn)}}"
                                            target="_blank"><span>申请退货</span></a></p>
                                <p class="p-link"><a href="{{url('home/user/comment/'.$list->order_id)}}" target="_blank"><span>评价</span></a></p>
                            @endif
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="6" class="te-al-le litz-pa">
                                <p>商品总价：<span class="co-red">{{$list->goods_price}}</span></p>
                                <!--
                                <p>邮费 :<span class="co-red">{$list->shipping_price}}</span></p>
                                <p>优惠券 :<span class="co-red">{$list->coupon_price}}</span></p>
                                <p>积分抵扣 :<span class="co-red">{$list->integral_money}}</span></p>
                                <p>活动优惠:<span class="co-red">{$list->order_prom_amount}}</span></p>
                                <p>余额 :<span class="co-red">{$list->user_money}}</span></p>
                                -->
                                <p>应付金额（含运费）：<span class="co-red fo-si-18">{{$list->total_amount}}</span></p>
                            </td>
                            <td rowspan="1" class="col-operate">
                                @if($list->order_status == 1)
                                    <p class="p-link"><a onClick="cancel_order({{$list->order_id}})" >取消订单</a></p>
                                    <p class="p-button">
                                    <a class="button-operate-pay di-in-bl hb-merge" href="javascript:toPay({{$list->order_id}})">
                                    <span>立即支付</span></a></p>
                                @endif
                                @if($list->order_status == 5)
                                    <p class="p-button"><a class="button-operate-pay di-in-bl hb-merge" onClick="confirm_order('{{$list->order_id}}')">
                                    <span>收货确认</span></a></p>
                                @endif
                                    <p class="p-link"><a href="{{url('home/order/orderDetail',['oid'=>$list->order_id])}}">订单详情</a></p>
                            </td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
                <!--订单列表结束-->
            </div>
            <div class="merge">
                <!--<label class="ma-ri-20 di-in-bl cu-po"  name="bottomCheckBoxDiv"><input name="bottomCheckBoxDiv" class="ma-ri-10  ve-al-mi" id="checkallbox" type="checkbox"><span class="ve-al-mi fo-si-14 fo-fa-ta">全选</span></label>-->
                <!--<a class=" di-in-bl hb-merge" href="">合并支付</a>-->
                {!! $pageObj->show() !!}
            </div>
        </div>
    </div>
</div>
<div class="he80"></div>
@stop

@section('javascript')
<script type="text/javascript">

    $(function () {
        //高亮显示
        var active = '{$active_status}';
        if(!active)
            active = 'ALL';
        $('.wddd-li li').removeClass('wddd-red');
        $('#'+active).addClass('wddd-red');

        $("#h-s").mouseover(function () {
            $('.ec-ta-x').css('left','124px');
            $('.ec-ta-x').css('width','110px');
            $(this).addClass("cullent");
        });
        $("#h-s").mouseout(function () {
            $('.ec-ta-x').css('left','0px');
            $('.ec-ta-x').css('width','124px');
            $(this).removeClass("cullent");
        });
    });
    $(function () {
        $("#q-s").mouseover(function () {
            $('.ec-ta-x').css('left','0px');
            $(this).addClass("cullent");
        });
        $("#q-s").mouseout(function () {
            $('.ec-ta-x').css('left','0px');
        });
    });
    //收货确认
    function confirm_order(oid){
        layer.confirm("确定收到包裹吗?",{
            btn:['确定','取消']
        },function(index,layero){
            $.post('{!! url("home/order/confirmOrder/'+oid+'") !!}',{"_token":"{{csrf_token()}}"},function(json){
                if(!json.status){
                    layer.msg(json.msg,{icon:1,time:2000},function () {
                        layer.closeAll();
                        location.reload();
                    });
                }
            },"json");
        },function(index){
            layer.close(index);
        });
    }
    //取消订单
    function cancel_order(oid){
        layer.confirm("确定取消订单?",{
            btn:['确定','取消']
        },function(index,layero){
            $.post('{!! url("home/order/concelOrder/'+oid+'") !!}',{"_token":"{{csrf_token()}}"},function(json){
                if(!json.status){
                    layer.msg(json.msg,{icon:1,time:2000},function () {
                        layer.closeAll();
                        location.reload();
                    });
                }
            },"json");
        },function(index){
            layer.close(index);
        });
        /*if(!confirm("确定取消订单?"))
            return false;
        location.href = "/index.php?m=Home&c=User&a=cancel_order&id="+id;*/
    }
    function toPay(orderid){
        var url = '{!! url("home/order/toPay/'+orderid+'?status=1") !!}';	// 新增地址
        {{--'{!!url("home/user/address/'+id+'/edit")!!}';--}}
//        alert(url);return ;
        $.post(url,{"_token":"{{csrf_token()}}"},function(data){
            layer.open({
                type:1,
                area: ['800px', '600px'],
                content:data,
            });
        });
        /*
        layer.open({
            type: 2,
            title: "订单支付",
            shadeClose: true,
            shade: 0.8,
            zIndex:500,
            area: ['800px', '600px'],
            content: [url,'no'],
            success:function(layero,index){
                var pay_suc = layer.getChildFrame('#pay_success', index);
                alert(pay_suc.href);
            },

            yes:function(index,layerobj){
                var data = $(layerobj).find("iframe")[0].contentWindow.checkForm();
                $.ajax({
                    type : "POST",
                    data : {"_token":"{csrf_token()}}","data":data},
                    url  : '{url("home/user/address")}}',
                    error: function(json) {
                        layer.alert(json.info, {icon: 2});
                        return;
                    },
                    success: function(json) {
                        layer.alert(json.info, {icon: 1});
                        layer.closeAll(); // 关闭窗口
                        ajax_address();
                    }
                });
            }
        });*/
    }
</script>
@stop