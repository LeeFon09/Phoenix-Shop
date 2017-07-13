<!DOCTYPE html><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>退换货列表</title>
    <meta http-equiv="keywords" content="{$tpshop_config['shop_info_store_keyword']}"/>
    <meta name="description" content="{$tpshop_config['shop_info_store_desc']}"/>
    <link rel="stylesheet" href="{{URL::asset('Static/css/index.css')}}" type="text/css">
    <link rel="stylesheet" href="{{URL::asset('Static/css/page.css')}}" type="text/css">
    <link rel="stylesheet" href="{{URL::asset('Static/css/outhu.css')}}" type="text/css">
    <script src="{{URL::asset('Static/js/jquery-1.10.2.min.js')}}"></script>
</head>

<body>
<!--------头部开始-------------->
{{--<include file="Public/header" /> --}}
@include('Home.Public.header')
<!--------头部结束-------------->

<div class="layout ov-hi">
    <div class="breadcrumb-area">
            <if condition="$k neq '首页'">首页 ></if>
            <a href="{{url('home/user/returnGoods')}}">退换货列表</a>
    </div>
</div>
<div class="layout pa-to-10 fo-fa-ar">
    <!--菜单-->
    {{--<include file="User/menu" /> --}}
    @include('Home.User.menu')
    <!--菜单-->
    <div class="fr wi940">
        <div id="main">
            <div class="mod-main mod-comm">
                <div class="mc">
                    <tbdoy></tbdoy>
                    <table class="tb-void tb-top">
                        <colgroup>
                            <col width="110">
                            <col width="120">
                            <col width="">
                            <col width="120">
                            <col width="120">
                            <col width="100">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>返修/退换货编号</th>
                            <th>订单编号</th>
                            <th>商品名称</th>
                            <th>申请时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($returnGoods as $v)
                            <tr>
                                <td align="center"><a target="_blank" href="">{{$v->id}}</a></td>
                                <td>
                                    <a target="_blank" href="{:U('Home/User/order_detail',array('id'=>$item['order_id']))}">{{$v->order_sn}}</a>
                                </td>
                                <td>
                                    <div>
                                        <a href="{:U('Home/Goods/goodsInfo',array('id'=>$item[goods_id]))}" target="_blank">{{$v->goods_name}}</a>
                                    </div>
                                </td>
                                <td>{{date("Y-m-d",$v->addtime)}}</td>
                                <td>
                                    @if($v->status == 0) 待客服处理 @endif
                                    @if($v->status == 1) 客服处理中 @endif
                                    @if($v->status == 2) 已完成     @endif
                                </td>
                                <td><a href="{{url("home/user/returnGoodsInfo/".$v->id)}}">查看</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt10 clearfix" style="text-align:center"></div>
            </div>
            <div class="mod-main mod-comm">
                <div class="mt">
                    <h3>返修/退换货申请常见问题</h3>
                </div>
                <div class="mc"> 1. “申请”按钮若为灰色，可能是因为订单尚未完成或该商品正在返修/退换货中;</div>
            </div>
        </div>
    </div>
</div>
<div class="he80"></div>
<!--------footer-开始-------------->
{{--<include file="Public/footer2" /> --}}
@include('Home.Public.footer')
<!--------footer-结束-------------->
</body>
</html>