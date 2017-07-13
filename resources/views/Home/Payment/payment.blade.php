<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>支付</title>
<meta http-equiv="keywords" content="" />
<meta name="description" content="" />
<style type="text/css">
*{ margin:0; padding:0}
.wihe-ee{width:560px; height:420px; background:#FFF; padding:25px; color:#666; font-family:song,arial; font-size:14px; box-sizing:border-box; border-radius:6px; margin:0 auto; margin-top:10%}
.wihe-ee p{text-align:center}
.co999{color:#999}
.fo-si-18{font-size:18px}
.fail-fasu{float:left; width:200px; height:180px; padding-top:100px; background:url('../../Static/images/icon-pay.png') 50px 0 no-repeat; text-align:center}
.success-fasu{float:right; width:200px; height:180px; padding-top:100px; background:url('../../Static/images/icon-pay.png') -220px 0 no-repeat; text-align:center}
.fail-fasu a:hover{ background-color:#ee9775}
.fail-fasu a{padding:8px 24px; background-color:#f8a584; display:table; margin:0 auto; color:#fff; text-decoration:none; margin-top:10px}
.re-qtzfgg a,.success-fasu a{padding:8px 24px; background-color:#eee; display:table; margin:0 auto; color:#999; text-decoration:none; margin-top:10px}
.re-qtzfgg a{padding:8px 140px}
.re-qtzfgg a:hover,.success-fasu a:hover{background-color:#ddd;}
.fail-success{overflow:hidden; height:185px}
</style>
<script src="{{asset('js/jquery-1.10.2.min.js')}}"></script>
<script src="{{asset('js/layer/layer.js')}}"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
</head>
<body style="background-color:#666">
	<div class="tac-sd">
    	<div class="wihe-ee">
        	<p>
            	<span class="fo-si-18">请您在新打开的页面上完成付款!</span>
                <br>
                <span class="co999">付款完成前请不要关闭此窗口。完成付款后请根据您的情况点击下面的按钮。
                </span><br/>订单号：{{$orderInfo->order_sn}}
            </p>
            <br>
            <br>
            <div class="fail-success">
            	<div class="fail-fasu">
                	支付完成
                    <br>
                    <a id="pay_success" onclick="doPayment('{{url('home/order/doPay/'.$orderInfo->order_id)}}',0)" href="javascript:;">支付成功</a>
                </div>
                <div class="fail-I-success" style="float:left">
                	<!--<img src="__STATIC__/images/qrcode_vmall_app01.png" width="110" height="110"/>-->
                </div>
            	<div class="success-fasu">
                	支付遇到问题
                    <br>
                    <a id="pay_error" href="javascript:doPayment('{{url('home/order/doPay/'.$orderInfo->order_id)}}',1);">支付失败</a>
                </div>
            </div>
           {{-- <div class="re-qtzfgg">
            	<a href="javascript:doPayment('{url('home/order/doPay/'.$orderInfo->order_id)}}',2);">返回选择其他支付方式</a>
            </div>--}}
        </div>
    </div>
</body>
<script type="text/javascript">
    function doPayment(url,op){
//        alert(url);return;
        if(op == 0){
            $.post(url,{"_token":"{{csrf_token()}}"},function(json){
                alert(json.msg);
                window.parent.location.reload();
            },"json");
        }else if(op == 1){
            layer.closeAll();
        }
    }
</script>
</html>
