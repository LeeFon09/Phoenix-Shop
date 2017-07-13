<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>确认订单</title>
<meta http-equiv="keywords" content="" />
<meta name="description" content="" />
<script src="{{asset('js/jquery-1.10.2.min.js')}}"></script>
<script src="{{asset('js/global.js')}}"></script>
<script src="{{asset('js/pc_common.js')}}"></script>
<script src="{{asset('js/layer/layer.js')}}"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
</head>
<body>
@include("Home/Public/siteTopbar")
    <div class="order-header">
    	<div class="layout after">
        	<div class="fl">
            	<div class="logo pa-to-36 wi345">
                	<a href="/"><!-- <img src="{$tpshop_config['shop_info_store_logo']}" alt=""> --></a>
                </div>
            </div>
        	<div class="fr">
            	<div class="pa-to-36 progress-area">
                	<div class="progress-area-wd" style="display:none">我的购物车</div>
                	<div class="progress-area-tx" style="display:block">填写核对订单信息</div>
                	<div class="progress-area-cg" style="display:none">成功提交订单</div>
                </div>
            </div>
        </div>
    </div>
<style>
	/*.address tr td { border:#F00 solid 1px; }*/
    /*没选中的 收货地址*/
    .order-address-list table{background-color:inherit; border: 0px solid #CCC;}
    .order-address-list .default{visibility:hidden;}
    /*选中的 收货地址*/	
    .address_current{ background-color:#fffde6; border: 1px solid #fadccf;} 
	.address_current .default{visibility:visible;}			
</style>
<form name="cart2_form" id="cart2_form" method="post">
    <div class="layout be-table fo-fa ma-bo-45">
    	<div class="con-info">
        	<div class="con-y-info ma-bo-35">
            	<h3 style="margin-top:30px">收货人信息<b>[<a href="javascript:void(0);" onClick="address_add()">使用新地址</a>]</b></h3>
                <div id="ajax_address"><!--ajax 返回收货地址--></div>
                {{--<h3 style="margin-top:30px">自提点</h3>
                <div id="ajax_pickup"><!--ajax 返回自提点--></div>--}}
            </div>
        	<div class="con-y-info ma-bo-35 con-h">
            	<h3>发票信息<em>(请谨慎选择发票抬头，订单出库后不能修改)</em></h3>
                <div class="order-invoice-list">
            		<ul>
            		<li>
                    	<div class="invoice-main fl"><label for="invoice_title">发票抬头:</label></div>
                        <div class="invoice-sub fl">
                        	<label class="inv-label">
                        		<input class="officdw" type="text" name="invoice_title" placeholder="XXX单位 或 XX个人" />
                            </label>
                        </div>
                    </li>
            	</ul>
            	</div>
                <p class="tips"><em>注意：</em>如果商品由第三方卖家销售，发票内容由其卖家决定，发票由卖家开具并寄出</p>
            </div>
            <!--
			<div class="con-y-info ma-bo-35 con-h">
            	<h3>物流信息<em>(选择相应的物流配送，订单出库后不能修改)</em></h3>
                <div class="order-invoice-list">
            		<ul>
                    <foreach name="shippingList" item="v"  key="k">
                        <li>
                            <div class="invoice-main">
                                <input id="{$v.code}" type="radio" name="shipping_code" value="{$v.code}" onClick="ajax_order_price();" <if condition="$k eq 0"> checked="checked" </if> />
                                <label for="gr">{$v.name}</label>
                                <em>({$v.desc})</em>
                            </div>
                        </li>
            		</foreach>
	            	</ul>
            	</div>
                <p class="tips"><em>注意：</em>如果商品由第三方卖家销售，发票内容由其卖家决定，发票由卖家开具并寄出</p>
            </div>
            -->
        </div>
        <div class="sc-area">
        	<div class="dt-order-area">
            	<div class="order-pro-list">
                	<div class="order-pro-list">
                    	<div class="yxspy">
                        	<div class="hv">您购买的以下商品</div>
                        	<div class="bv">
                            	<table border="0" cellpadding="0" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="tr-pro">商品</th>
                                            <th class="tr-price">单价（元）</th>
                                            {{--<if condition="($user[discount] neq 1)">--}}
                                            	{{--<th class="tr-price">会员折扣价</th>--}}
                                            {{--</if>--}}
                                            <th class="tr-quantity">数量</th>
                                            <th class="tr-subtotal">小计（元）</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="leiliste">
                        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                            @foreach($cartList as $v)
                            <tr>
                                <td class="tr-pro">
                                    <ul class="pro-area-2">
                                        <li><img class="wi63 hi63" src="{{$v['original_img']}}" style="float:left;"/>
                                            <a style="float:left;" title="{{$v["goods_name"]}}" target="_blank" href="{{url('home/goods/goodsinfo',['id'=>$v['goods_id']])}}" seed="item-name">
                                                &nbsp;&nbsp;&nbsp;&nbsp;{{$v["goods_name"]}}</a><br><p class="ggwc-ys-hs">&nbsp;&nbsp;&nbsp;&nbsp;{{ $v['spec_key_name']}}</p>
                                        </li>
                                     </ul>
                                 </td>
                                <!-- 预付订金商品的价格为空 -->
                                <td class="tr-price te-al">¥{{$v["goods_price"]}}</td>
                                {{--<if condition="($user[discount] neq 1)">--}}
                                    {{--<td class="tr-price te-al">¥{$v.member_goods_price}</td>--}}
                                {{--</if>--}}
                                <td class="tr-quantity te-al">{{$v["goods_num"]}}</td>
                                <td rowspan="1" class="tr-subtotal te-al">
                                <p><b>￥{{$v["total_price"]}}</b></p>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              <div class="order-pro-total">
                	<div class="fl wctmes">
                        <div class="syyouhui pa-to-15">
                        	<div class="duoxuk">
                            	 <label class="fo-fa-ta" for="order-chick">使用优惠券:</label>&nbsp;&nbsp;注：优惠券每次只能使用一张，不可多张混合使用
                       		</div>
                            <div class="byicd">
                            	<div class="zhiwfnka">
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                <input type="radio" class="radio vam ma-ri-10" name="couponTypeSelect" checked value="1"  onClick="ajax_order_price();" />
                                                 <select id="coupon_id" name="coupon_id" class="vam ou-no" onChange="ajax_order_price();">
                                                     <option value="0">选择优惠券</option>
                                                      <foreach name="couponList" item="v"  key="k">
                                                        <option value="{$v['id']}">{$v['name']}</option>
                                                      </foreach>
                                                 </select>
                                                &nbsp;&nbsp;&nbsp;
                                                <input type="radio" class="radio vam ma-ri-10" name="couponTypeSelect" value="2"  onClick="ajax_order_price();" />
                                                <input type="text" name="couponCode" class="texter vam span-150 ma-ri-10 he18 li-he-18" placeholder="请输入优惠券代码" />
                                                <input type="button" class="button-style-disabled-4 button-action-use-disabled te-al ou-no vam" value="使用" onClick="ajax_order_price();" />
                                                </td>
                                            </tr>
                                            <!--
                                            <tr>
                                                <td>
                                                <label class="fo-fa-ta" for="order-chick">积分支付:</label>
                                                <input type="text" id="pay_points" name="pay_points" class="texter vam span-150 ma-ri-10 he18 li-he-18" placeholder="请输入使用积分"    onpaste="this.value=this.value.replace(/[^\d]/g,'')" onKeyUp="this.value=this.value.replace(/[^\d]/g,'')"/>
                                                <input type="button" class="button-style-disabled-4 button-action-use-disabled te-al ou-no vam" value="使用" onClick="ajax_order_price();" />
                                                 积分抵 1元 &nbsp;&nbsp;   您的可用积分 {$user['pay_points']} 分
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                <label class="fo-fa-ta" for="order-chick">余额支付:</label>
                                                <input type="text" id="user_money" name="user_money" class="texter vam span-150 ma-ri-10 he18 li-he-18" placeholder="请输入使用余额"   onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"/>
                                                <input type="button" class="button-style-disabled-4 button-action-use-disabled te-al ou-no vam" value="使用" onClick="ajax_order_price();" />
                                                您的可用余额 ¥ {$user['user_money']}
                                                </td>
                                            </tr>
                                            -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="fr wcnhy">
                    	<div class="fzoubddv">
                        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td class="tal">商品总金额：</td>
                                        <td class="tar">&nbsp;¥&nbsp;
                                           <em id="order-cost-area">{{$total}}</em>
                                        </td>
                                    </tr>
                                    <!--
                                    <tr>
                                        <td class="tal">运费：</td>
                                        <td class="tar">&nbsp;¥&nbsp;
                                               <em id="postFee">0.00</em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tal">使用优惠券：</td>
                                        <td class="tar">-&nbsp;¥&nbsp;
                                          <em><span id="couponFee">0.00</span> </em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tal">使用积分：</td>
                                        <td class="tar">-&nbsp;¥&nbsp;
                                          <em><span id="pointsFee">0.00</span> </em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tal">优惠活动：</td>
                                        <td class="tar">-&nbsp;¥&nbsp;
                                          <em><span id="order_prom_amount">0.00</span> </em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tal">使用余额:</td>
                                        <td class="tar">-&nbsp;¥&nbsp;
                                          <em><span id="balance">0.00</span> </em>
                                        </td>
                                    </tr>
                                    -->
                                </tbody>
                            </table>
                            <p class="yifje-order">
                            	<span class="p-subtotal-price"> 应付金额：
                                    <b class="total">¥</b>
                                    <b class="total" id="payables">{{$total}}</b>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-action-area te-al-ri">
            	<div class="woypdbe sc-acti-list pa-to-20">
                	<!--<span class="p-subtotal-price">应付总额：<b>¥<span class="vab" id="payableTotal">2276.00</span></b></span>-->
                    <a class="Sub-orders gwc-qjs" href="javascript:void(0);" onClick="submit_order();"><span>提交订单</span></a>
                    <a class="Sub-orders gwc-qjs" href="{{url("home/cart/cart")}}" ><span>返回购物车</span></a>
                    <br/><br/>
                    {{--<a class="Sub-orders gwc-qjs" href="javascript:getPage();" ><span>订单支付</span></a>--}}
                </div>
            </div>
        </div>
    </div>
</form>    
<!--------footer-开始-------------->
@include("Home.Public.footer")
<!--------footer-结束-------------->

<script>
$(document).ready(function(){
	ajax_address(); // 获取用户收货地址列表
});
/**
 * 新增收货地址
 */
function address_add(){
    var url = '{{url('home/user/address/create')}}';	// 新增地址
    layer.open({
        type: 2,
        title: '添加收货地址',
        shadeClose: true,
        shade: 0.8,
        btn:['保存','取消'],
        zIndex:500,
        area: ['800px', '500px'],
        content: [url,'no'],
        yes:function(index,layerobj){
            var data = $(layerobj).find("iframe")[0].contentWindow.checkForm();
            if(data != undefined){
                $.ajax({
                    type : "POST",
                    data : {"_token":"{{csrf_token()}}","data":data},
                    url  : '{{url("home/user/address")}}',
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
        }
    });
}
/**
 * 修改收货地址
 * @param id
 */
function address_edit(id) {
    var url = '{!!url("home/user/address/'+id+'/edit")!!}';
    layer.open({
        type: 2,
        title: '修改收货地址',
        shadeClose: true,
        shade: 0.8,
        btn:['保存','取消'],
        zIndex:500,
        area: ['800px', '500px'],
        content: [url,'no'],
        yes:function(index,layerobj){
            var data = $(layerobj).find("iframe")[0].contentWindow.checkForm();
            if(data != undefined){
                $.ajax({
                    type : "PUT",
                    data : {"_token":"{{csrf_token()}}","data":data},
                    url  : '{{url("home/user/address")}}/'+id,
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
        }
    });
}
/*
 * ajax 获取当前用户的收货地址列表
 */
function ajax_address(){
    $.get("{{url('home/cart/cneeInformation')}}",function(data){
        $("#ajax_address").html('');
        $("#ajax_address").append(data);
    });
}
// 删除收货地址
function del_address(id) {
    if(!confirm("确定要删除吗?"))
	  return false;

    $.ajax({
        url:'{!!url("home/user/address/'+id+'")!!}',
        success: function(data){
            layer.alert('删除成功!', {icon: 1});
            ajax_address(); // 刷新收货地址
            $('#ajax_pickup .order-address-list').removeClass('address_current');
        }
    });
}





// 切换收货地址
function swidth_address(obj)
{
//    var province_id = $(obj).attr('data-province-id');
//    var city_id = $(obj).attr('data-city-id');
//    var district_id = $(obj).attr('data-district-id');
//    if (typeof($(obj).attr('data-province-id')) != 'undefined') {
//        ajax_pickup(province_id,city_id,district_id,0);
//    }
    $(".order-address-list").removeClass('address_current');
    $(obj).parent().parent().parent().parent().parent().addClass('address_current');
//    if($('#address_id').length > 0)
//        ajax_order_price(); // 计算订单价格
}

/**
 * 获取用户自提点和推荐自提点
 * @param type 1：用户自提点 ，0：用户自提点和推荐自提点
 * @param province_id 省
 * @param city_id 市
 * @param district_id 区
 */
function ajax_pickup(province_id, city_id, district_id,show) {
    $.ajax({
        type: 'GET',
        url: "{:U('Home/Cart/ajaxPickup')}",//+tab,
        data: {province_id: province_id, city_id: city_id, district_id: district_id,show:show},
        success: function (data) {
            $("#ajax_pickup").html('');
            $("#ajax_pickup").append(data);
        }
    });
}
//更换自提点
function replace_pickup(province_id, city_id, district_id) {
    var url = "/index.php?m=Home&c=Cart&a=replace_pickup&call_back=call_back_pickup&province_id="+province_id+"&city_id="+city_id+"&district_id="+district_id;
    layer.open({
        type: 2,
        title: '添加收货地址',
        shadeClose: true,
        shade: 0.8,
        area: ['880px', '580px'],
        content: url,
    });
}
// 添加自提点地址回调函数
function call_back_pickup(province_id,city_id,district_id){
    layer.closeAll(); // 关闭窗口
    ajax_pickup(province_id, city_id, district_id,1);
}


// 获取订单价格
function ajax_order_price()
{

	$.ajax({
		type : "POST",
		url:"{:U('Home/Cart/cart3')}",//+tab,
		data : $('#cart2_form').serialize()+"&act=order_price",// 你的formid
        dataType: "json",
		success: function(data){

			if(data.status != 1)
			{
				// alert(data.msg); //执行有误
				layer.alert(data.msg, {icon: 2});

				// 登录超时
				if(data.status == -100)
					location.href ="{:U('Home/User/login')}";

				return false;
			}
			// console.log(data);
			//$("#couponFee, #pointsFee, #order_prom_amount").css('display','none');

			$("#postFee").text(data.result.postFee); // 物流费
			$("#couponFee").text(data.result.couponFee);// 优惠券
			$("#balance").text(data.result.balance);// 余额
			$("#pointsFee").text(data.result.pointsFee);// 积分支付
			$("#payables").text(data.result.payables);// 应付
			$("#order_prom_amount").text(data.result.order_prom_amount);// 订单 优惠活动
		}
	});
}

// 提交订单
var ajax_return_status = 1;
function submit_order()
{
	if(ajax_return_status == 0){
        console.log("return false!!!");
        return false;
    }

	ajax_return_status = 0;
    var data = $('#cart2_form').serializeArray();
    var json = {};
    $.each(data,function(index,item) {
        json[item.name]=item.value;
    });
//    console.log(JSON.stringify(json));
	$.ajax({
		type : "POST",
		url:"{{url('home/cart/commitOrder')}}",//+tab,
		data : {"_token":"{{csrf_token()}}","data":JSON.stringify(json)},
        dataType: "json",
        async:false,
		success: function(json){

			if(json.status > 0){
				// alert(data.msg); //执行有误
				layer.alert(json.info, {icon: 2});
				ajax_return_status = 1; // 上一次ajax 已经返回, 可以进行下一次 ajax请求

				return false;
			}

            layer.msg('订单提交成功，跳转支付页面!', {icon: 1,time: 2000}, function(){
                console.log(json.oid);
                location.href = '{!!url("home/cart/checkPayment/'+json.oid+'")!!}'; // 跳转到结算页
            });
		},error:function (json) {
            console.log(JSON.stringify(json));
        }
	});
}

</script>
</body>
</html>
