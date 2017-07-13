<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>购物车--凤凰涅槃</title>
<meta http-equiv="keywords" content="" />
<meta name="description" content="" />
<script src="{{url('js/jquery-1.10.2.min.js')}}"></script>

<script src="{{url('js/global.js')}}"></script>
<script src="{{url('js/pc_common.js')}}"></script>
<script src="{{url('js/layer/layer.js')}}"></script><!--弹窗js 参考文档 http://layer.layui.com/-->
<script src="{{url('js/md5.js')}}"></script>

</head>

<body>
{{--{{session()->forget("cart")}}--}}
    @include("Home.Public.siteTopbar")
    <div class="order-header">
    	<div class="layout after">
        	<div class="fl">


            	<div class="logo pa-to-36 wi345">
                	<a href="{{url('/')}}"><img src="" alt=""></a>
                </div>

            </div>
        	<div class="fr">
            	<div class="pa-to-36 progress-area">
                	<div class="progress-area-wd">我的购物车</div>
                	<div class="progress-area-tx" style="display:none">填写核对订单信息</div>
                	<div class="progress-area-cg" style="display:none">成功提交订单</div>
                </div>
            </div>
        </div>
    </div>

    <div class="layout after-ta">
    	<div class="sc-list">        
            <form name="cart_form" id="cart_form" action="{{url('home/cart/checkOrder')}}" method="post">
                {{csrf_field()}}
                <div id="ajax_return"><!--  ajax renturn --></div>
            </form>
            <div class="sc-acti-list ma-to-20 ma-bo-135">
            	<a class="gwc-jxgw" href="{{url('/')}}">继续购物</a>
                <a class="gwc-qjs" href="javascript:to_check_order();" >去结算</a>
            </div>
        </div>
    </div>
<!--------footer-开始-------------->
    @include("Home.Public.footer")
<!--------footer-结束-------------->
<script>

$(document).ready(function(){
			
	ajax_cart_list(); // ajax 请求获取购物车列表
});
function to_check_order(){
    var chk_goods = $("input[name^='cart_select']:checked");
    if(chk_goods.length == 0){
        layer.alert("请选择购物车内商品!",{icon:2});
    }else {
        {{--location.href="{{url('home/cart/checkOrder')}}?token="+hex_md5("checkorder");--}}
       $("#cart_form").submit();
    }
}


// ajax 提交购物车
var before_request = 1; // 上一次请求是否已经有返回来, 有才可以进行下一次请求


function ajax_cart_list(parse=-1){

    {{--isset({{$selected}})?{{$selected}}:"";--}}
//    var box = $("input[name='cart_select["+parse+"]']").val();
	if(before_request == 0) // 上一次请求没回来 不进行下一次请求
	    return false;
	before_request = 0;

	var json = JSON.stringify($('#cart_form').serializeArray());
	$.ajax({
		type : "POST",
		url:"{{url('home/cart/ajaxList')}}",//+tab,
		data : {"aa":$('#cart_form').serialize(), "selected":parse , "_token":"{{csrf_token()}}" },// 你的formid
		success: function(data){
//            console.log(data);
			$("#ajax_return").html("");
			$("#ajax_return").append(data);
			before_request = 1;
		}
	});
}


 
// ajax 删除购物车的商品
function ajax_del_cart(ids, del=0)
{
	$.ajax({
		type : "POST",
		url:"{{url('home/cart/ajaxDel')}}",//+tab,
		data:{"ids":ids, "del":del ,"_token":"{{csrf_token()}}" }, //
	    dataType:'json',		
		success: function(data){
//		  console.log(data);
		   if(data.status == 1 && del == 0)
				ajax_cart_list(); // ajax 请求获取购物车列表		   			   
		}
	});
}

// 批量删除购物车的商品
function del_cart_more()
{
	if(!confirm('确定要删除吗?'))
	  return false;
	// 循环获取复选框选中的值  
	var chk_value = [];
//	var chk_value;
	var del = 1;

	$('input[name^="cart_select"]:checked').each(function(i){
//		var s_name = $(this).attr('name');
//		var id = s_name.replace('cart_select[','').replace(']','');
//		chk_value.push(id);
        chk_value[i] = $(this).val();
	});

	$.get("{{url('home/cart/delMore')}}",{"chk":chk_value}, function(data){
        console.log(data);
    });

    ajax_cart_list(); // ajax 请求获取购物车列表


}
</script>
</body>
</html>
