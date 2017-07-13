<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>登录</title>
    <meta http-equiv="keywords" content="{$tpshop_config['shop_info_store_keyword']}" />
    <meta name="description" content="{$tpshop_config['shop_info_store_desc']}" />
    <link rel="stylesheet" href="{{URL::asset('Static/css/index.css')}}" type="text/css">
    <script src="{{URL::asset('Static/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{URL::asset('Static/js/slider.js')}}"></script>
</head>

<body class="regs">
<div class="customer-header">
    <div class="setout bit-ti-s po-re">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><a href="/"><img src="{$tpshop_config['shop_info_store_logo']}" alt="logo"></a></td>
                <td class="pa-le-5"><img src="{{URL::asset('Static/images/split1.png')}}" /></td>
                <td><span>TPshop商城</span></td>
            </tr>
        </table>
    </div>
</div>
<div class="setout">
    <div class="login-area">
        <div class="wel-log">
            <h3><span>欢迎登录</span></h3>
        </div>
        <form action="{:U('Home/User/do_login')}" method="post">
            <input type="hidden" name="referurl" id="referurl" value="{$referurl}">
            <input type="hidden" name="parse" id="parse" value="{{$parse or ""}}">
            <div class="login-x">
                <div class="ma-to--26">
                    <table class="pa-10-0" width="100%" border="0" cellspacing="0" cellpadding="0">
                    	<tr>
                            <td><span class="co_ye">测试账号:13800138006&nbsp;&nbsp;测试密码:123456</span></td>
                        </tr>
                        <tr>
                            <td><input class="zc-text" type="text" id="username" name="username" placeholder="手机号/邮箱"/></td>
                        </tr>
                        <tr>
                            <td><input class="zc-text" name="password" id="password" type="password" placeholder="密码（8-32个字符）"/></td>
                        </tr>
                        <tr>
                            <td class="po-re">
                                <input class="zc-text wi96" type="text" name="verify_code" id="verify_code" placeholder="不区分大小写"/>
                                <img class="po-ab to0" id="verify_code_img" width="120" height="40" src="{{url('/code')}}"  onclick="verify(this)" />
                                <a><img class="ma-le-142 po-ab to18" src="{{URL::asset('Static/images/chg_image.png')}}" id="verify_code_img2"  onclick="verify(this)" /></a>
                            </td>
                        </tr>
                        {{--<tr>--}}
                            {{--<td class="pa-to-30">--}}
                                {{--<input class="ma-ri-5 fo-fa-ta" type="checkbox" id="remember_name"/><label for="remember_name">记住用户名</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
                                {{--<a class="fo-fa-ta co929292" href="{:U('User/forget_pwd')}" >忘记密码？</a>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td><input type="button" class="login" onClick="checkSubmit()" value="登录"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </form>
        <span class="co666">使用合作账号登录：</span>
        <span><a class="hezuoz" href="{:U('LoginApi/login',array('oauth'=>'alipay'))}"><i class="zhi-y-f"></i></a></span>
        <span><a class="hezuoz" href="{:U('LoginApi/login',array('oauth'=>'qq'))}"><i class="ba-po--185"></i></a></span>
        <p class="nu-miaf">没有账号？&nbsp;&nbsp;<a href="{{url('/reg')}}">免费注册</a></p>
        <p class="nu-miaf nu-miah"><label for=""><!-- 继续登录表明您已充分理解并同意以上内容。 --></label></p>
    </div>
</div>
<div class="hr-60"></div>
<div class="cust-footer">
    <div class="setout po-re">
        <div class="warrant-area">
            <p>
                <a href="">《TPshop帐号服务条款、隐私政策》</a>
                <em>|</em>
                <a href="">常见问题？</a>
            </p>
	        <p>Copyright © 2016-2025 {$tpshop_config['shop_info_store_name']}  版权所有 保留一切权利 备案号:{$tpshop_config['shop_info_record_no']}</p>
        </div>
    </div>
</div>
<script>
    function verify(){
       $('#verify_code_img').attr("src","{{url('/code?')}}"+Math.random());
    }
    
	function checkSubmit()
	{
		var username = $.trim($('#username').val());
		var password = $.trim($('#password').val());
		var referurl = $('#referurl').val();
		var verify_code = $.trim($('#verify_code').val());
		if(username == ''){
			showErrorMsg('用户名不能为空!');
			return false;
		}
		if(!checkMobile(username) && !checkEmail(username)){
			showErrorMsg('账号格式不匹配!');
			return false;
		}
		if(password == ''){
			showErrorMsg('密码不能为空!');
			return false;
		}
		if(verify_code == ''){
			showErrorMsg('验证码不能为空!');
			return false;
		}
		
//		$.ajax({
//			type : 'post',
//			url : '/index.php?m=Home&c=User&a=do_login&t='+Math.random(),
//			data : {username:username,password:password,referurl:referurl,verify_code:verify_code},
//			dataType : 'json',
//			success : function(res){
//				if(res.status == 1){
//					window.location.href = res.url;
//				}else{
//					showErrorMsg(res.msg);
//					verify();
//				}
//			},
//			error : function(XMLHttpRequest, textStatus, errorThrown) {
//				showErrorMsg('网络失败，请刷新页面后重试');
//			}
//		})

        var parse = $("#parse").val();
        $.post("{{url('login')}}", {
            '_token': "{{csrf_token()}}",
            'username':username,
            'password':password,
            'verify_code':verify_code
        }, function (data) {
            if(data.status){
                showErrorMsg(data.msg);
                verify();
            }else{
                if(parse){
                    //跳转到购物车第二部，填写核对订单
                    location.href = "{{url('home/cart/checkOrder')}}";
                    return;
                }
                location.href = "{{url('/')}}";
            }
        });
	}
	
    function checkMobile(tel) {
        var reg = /(^1[3|4|5|7|8][0-9]{9}$)/;
        if (reg.test(tel)) {
            return true;
        }else{
            return false;
        };
    }
    
    function checkEmail(str){
        var reg = /^[a-z0-9]([a-z0-9\\.]*[-_]{0,4}?[a-z0-9-_\\.]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+([\.][\w_-]+){1,5}$/i;
        if(reg.test(str)){
            return true;
        }else{
            return false;
        }
    }
    
    function showErrorMsg(msg){
    	alert(msg);
    }
</script>
</body>
</html>