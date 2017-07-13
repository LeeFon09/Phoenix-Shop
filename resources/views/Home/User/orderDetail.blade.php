@extends('layouts.home')

@section('title','订单详情')

@section('content')
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
        <div class="cancel-order-detail-state">
            <!-- 20141223-订单进度-start -->
            <div class="order-state-progress">
                <ol style="margin-left:0;">
                    <li class="first completed">
                        <s></s>
                        <dl>
                            <dt>提交订单</dt>
                            <dd>
                                {{substr($orderInfo->commit_time,0,10)}}
                                {{substr($orderInfo->commit_time,10)}}
                            </dd>
                        </dl>
                    </li>
                    <li class="@if($orderInfo->order_status >= 1) current @endif ">
                        <s></s>
                        <dl>
                            <dt>待付款</dt>
                            @if(isset($orderInfo->pay_time))
                            <dd>
                                {{substr($orderInfo->pay_time,0,10)}}
                                {{substr($orderInfo->pay_time,10)}}
                            </dd>
                            @endif
                        </dl>
                    </li>
                    <li class="@if($orderInfo->order_status >= 3) current @endif ">
                        <s></s>
                        <dl>
                            <dt>待发货</dt>
                            @if(isset($orderInfo->shipping_time))
                                <dd>
                                    {{substr($orderInfo->shipping_time,0,10)}}
                                    {{substr($orderInfo->shipping_time,10)}}
                                </dd>
                            @endif
                        </dl>
                    </li>
                    <li class="@if($orderInfo->order_status >= 5) current @endif">
                        <s></s>
                        <dl>
                            <dt>待收货</dt>
                            @if(isset($orderInfo->confirm_time))
                                <dd>
                                    {{substr($orderInfo->confirm_time,0,10)}}
                                    {{substr($orderInfo->confirm_time,10)}}
                                </dd>
                            @endif
                        </dl>
                    </li>
                    <li class="@if($orderInfo->order_status >= 7) current @endif">
                        <s></s>
                        <dl>
                            <dt>待评价</dt>
                            @if(isset($orderInfo->comment_time))
                                <dd>
                                    {{substr($orderInfo->comment_time,0,10)}}
                                    {{substr($orderInfo->comment_time,10)}}
                                </dd>
                            @endif
                        </dl>
                    </li>
                    <li class="@if($orderInfo->order_status >= 9) current @endif">
                        <s></s>
                        <dl>
                            <dt>交易完成</dt>

                        </dl>
                    </li>
                </ol>
            </div>
            <!-- 20141223-订单进度-end -->
        </div>
        {{--
        <!-- 订单处理信息-start -->
        <div class="o-info o-inff">
            <div class="fl"><span class=" ma-ri-15 fo-si-16">订单处理信息</span></div>
        </div>
        <div class="wddd-js ov-in">
            <div class="list-group-title">
                <table class="merge-tab" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th class=" wi169">处理时间</th>
                        <th class="">处理信息</th>
                        <!-- <th class=" wi178" style="border-right:0">操作人</th> -->
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="merge-list">
                <div class="ma-0--1">
                    <div class="o-pro">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                            <tr>
                                <td class="col-pro-info  bo-les te-al wi169 sdesd co-red">{$orderInfo->commit_time}}</td>
                                <td class="te-al-le bo-les co-red te-al">{$action.action_note}</td>
                                <!-- <td rowspan="1" class="wi178 bo-les">{$action.action_user}</td>-->
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- 订单处理信息-end-->
        --}}
        <!-- 物流信息-s
        <notempty name="express.data">
        <div class="o-info o-inff">
            <div class="fl">
                <span class=" ma-ri-15 fo-si-16">物流信息</span>
            </div>
        </div>
        <div class="order-track">
              <div class="track-list">
                <ul>
                  <foreach name="express.data" item="vo" key="k">
	                  <if condition="$k eq 0">
	                  <li class="first"><i class="node-icon"></i><span class="time">{$vo.time}</span><span class="txt">{$vo.context}</span></li>
	                  <else/>
	                  <li><i class="node-icon"></i><span class="time">{$vo.time}</span><span class="txt">{$vo.context}</span></li>
	                  </if>
                  </foreach>
                </ul>
              </div>
        </div>
        </notempty>
        -->
        <!-- 物流信息-e -->
        <!-- 收货信息-start -->
        <div class="order-detail-receive">
            <div class="o-info o-inff">
                <div class="fl">
                    <span class=" ma-ri-15 fo-si-16">收货信息</span>
                </div>
            </div>
            <div class="b">
                <div class="form-info-panels">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <th>收货人姓名：</th>
                            <td>{{$address->consignee}}</td>
                        </tr>
                        <tr>
                            <th>收货地址：</th>
                            <td>{{$address->province_name}}，{{$address->city_name}}，{{$address->area_name}}，{{$address->town_name}}</td>
                        </tr>
                        <tr>
                            <th>详细地址：</th>
                            <td>{{$address->address}}</td>
                        </tr>
                        <tr>
                            <th>邮政编码：</th>
                            <td>{{$address->zipcode}}</td>
                        </tr>
                        <tr>
                            <th>联系电话：</th>
                            <td>{{$address->mobile}}</td>
                        </tr>
                        <tr>
                            <th>发票信息：</th>
                            <td>{{$orderInfo->invoice_title}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- 收货信息-end -->
        <!-- 商品清单-start -->
        <div class="o-info o-inff pi">
            <div class="fl">
                <span class=" ma-ri-15 fo-si-16">订单号：<a href="javascript:void(0);">{{$orderInfo->order_sn}}</a></span>
            </div>
        </div>
        <div class="list-group-title">
            <table class="merge-tab" border="0" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th class="col-pro">商品</th>
                    <th class="col-price">单价/元</th>
                    <th class="col-price">会员价</th>                    
                    <th class="col-quty">数量</th>
                    <th class="col-pay">小计/元</th>
                    <th class="col-operate">状态</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="merge-list">
            <div class="ma-0--1">
                <div class="o-pro">
                    <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    @foreach($details as $goods)
                    <tr>
                        <td class="col-pro-img">
                            <p>
                                <a title="{{$goods->goods_name}}" href="{{url('home/goods/goodsinfo',['id'=>$goods->goods_id])}}" target="_blank">
                                    <img src="{$goods.goods_id|goods_thum_images=78,78}" style="width: 78px;height: 78px;">
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
                            <if condition="($list[return_btn] eq 1) and ($goods[is_send] eq 1)">
                                <p class="p-link">
                                    <a style="color:#999;" href="{:U('Home/User/return_goods',}">申请退款</a>
                                </p>
                            </if>
                            <if condition="($list[comment_btn] eq 1) and ($goods[is_comment] eq 0)">
                                <p class="p-link"><a href="{{url('Home/User/comment')}}" target="_blank"><span>评价</span></a></p>
                            </if>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="fr wcnhy">
                <div class="fzoubddv" style="background-color:#FFF">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td class="tal">商品总金额：</td>
                            <td class="tar">&nbsp;¥&nbsp;
                                <em id="order-cost-area">{{$orderInfo->goods_price}}</em>
                            </td>
                        </tr>
                        <!--
                        <tr>
                            <td class="tal">运费：</td>
                            <td class="tar">&nbsp;¥&nbsp;
                                <em id="order-deliveryCharge">{$orderInfo->shipping_price}}</em>
                            </td>
                        </tr>
                        <tr>
                            <td class="tal">积分：</td>
                            <td class="tar">-&nbsp;¥&nbsp;
                                <em><span id="oreder-huaban-num">{$orderInfo->integral_money}}</span> </em>
                            </td>
                        </tr>
                        <tr>
                            <td class="tal">使用优惠券：</td>
                            <td class="tar">-&nbsp;¥&nbsp;
                                <em><span id="order-couponDiscount">{$orderInfo->coupon_price}}</span> </em>
                            </td>
                        </tr>

                        <tr>
                            <td class="tal">余额
                            </td>
                            <td class="tar">-&nbsp;¥&nbsp;<em>{$orderInfo->user_money}}</em>
                            </td>
                        </tr>
                        <tr>
                            <td class="tal">优惠活动
                            </td>
                            <td class="tar">-&nbsp;¥&nbsp;<em>{$orderInfo->order_prom_amount}}</em>
                            </td>
                        </tr>      -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="order-action-area te-al-ri cl-bo">
                <div class="woypdbe sc-acti-list" style="background-color:#f5f5f5; padding:10px 0 20px 0">
                    <span class="p-subtotal-price">合计（含运费）：<b>¥&nbsp;&nbsp;<span class="vab" id="payableTotal">{{$orderInfo->total_amount}}</span></b></span>
                </div>
            </div>
            <div class="woypdbe sc-acti-list pa-to-20">                    
                <if condition="$order_info.cancel_btn eq 1">
                    <a class="Sub-orders gwc-qjs" onClick="cancel_order({{$orderInfo->order_id}})"><span>取消订单</span></a>
                </if>
                <if condition="$order_info.pay_btn eq 1">
                    <a class="Sub-orders gwc-qjs" href="{:U('/Home/Cart/cart4',array('order_id'=>$order_info[order_id]))}"><span>立即支付</span></a>
                </if>
                <if condition="$order_info.receive_btn eq 1">
                    <a class="Sub-orders gwc-qjs" onClick=" if(confirm('你确定收到货了吗?')) location.href='{:U('Home/User/order_confirm',array('id'=>$order_info['order_id']))}'"><span>收货确认</span></a>
                </if>
            </div>
        </div>
        <!-- 商品清单-end -->
    </div>
</div>
<div class="he80"></div>
@stop

@section('javascript')
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
    //取消订单
    function cancel_order(id){
        if(!confirm("确定取消订单?"))
            return false;
        location.href = "/index.php?m=Home&c=User&a=cancel_order&id="+id;
    }
</script>
@stop