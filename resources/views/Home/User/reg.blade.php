<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>注册</title>
    <meta http-equiv="keywords" content="{$tpshop_config['shop_info_store_keyword']}" />
    <meta name="description" content="{$tpshop_config['shop_info_store_desc']}" />
    <link rel="stylesheet" href="{{URL::asset('Static/css/index.css')}}" type="text/css">
    <script src="{{URL::asset('Static/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{URL::asset('Static/js/slider.js')}}"></script>
    <script src="{{URL::asset('js/layer/layer.js')}}"></script><!--弹窗js 参考文档 http://layer.layui.com/-->
    <script src="{{URL::asset('js/global.js')}}"></script>
</head>

<body class="reg">
<div class="customer-header">
    
</div>
<div class="setout">
    <div class="ema-tel">
        <a class="ema-tab  tel-tab fr di-bl" href="#" id="tel_tab" onClick="reg_tab('tel_tab','ema_tab')">手机号码注册</a>
        <a class="tel-tab fr di-bl" href="#" id="ema_tab" onClick="reg_tab('ema_tab','tel_tab')">电子邮箱注册</a>
    </div>
    <!--邮箱注册-->
    <div class="shuru-area" id="ema_tab_div" style="display: none">
        <div class="tit-zc">
            <h3><span>邮箱注册</span></h3>
        </div>
        <div class="shrk-bd-zc">
            <form action="{{url('reg')}}" method="post" onSubmit=" return check_submit(this)" id="reg_form">
                {{csrf_field()}}
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <th>邮箱：</th>
                        <td><input class="zc-text" type="text" name="username" id="username" placeholder="请输入邮箱" required/></td>
                    </tr>
                     <if condition="$regis_smtp_enable eq 1">
                         <tr>
                             <th>邮箱验证码：</th>
                             <td  class="po-re">
                                 <input class="zc-text wi96" name="code" id="code" type="text" placeholder="邮箱验证码" required/>
                                 <button type="button" class="po-ab to0" style="width: 60px;height: 35px;margin-top: 10px;margin-left: 10px" name="count_down" disabled onClick="send_smtp_reg_code(this)">免费获取</button>
                             </td>
                         </tr>
                     </if>
                    <tr>
                        <th>密码：</th>
                        <td>
                            <input class="zc-text" type="password" name="password" id="password" placeholder="请输入6-16位密码" maxlength="16" required/>
                        </td>
                    </tr>
                    <tr>
                        <th>确认密码：</th>
                        <td><input class="zc-text" type="password" name="password2" id="password2" placeholder="请重复输入您的密码" required/></td>
                    </tr>
                    <tr>
                        <th>验证码：</th>
                        <td  class="po-re">
                            <input class="zc-text wi96" name="verify_code" id="verify_code" type="text" placeholder="不区分大小写" required/>
                            <img class="po-ab to0" width="140" height="65" id="reflsh_code"  src="{{url('/code')}}" onClick="verify('reflsh_code')" />
                            <a><img class="ma-le-210" src="{{URL::asset('Static/images/chg_image.png')}}" onClick="verify('reflsh_code')" /></a>
                        </td>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <td id="pa"><input class="checkboxs" name="agree" id="agree" type="checkbox" value="1" required/><label for="agree">同意</label><a class="iages" href="">《帐号服务条款、隐私政策》</a></td>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <td><input type="submit" value="立即注册" class="zc-ljzc"/></td>
                    </tr>
                </table>
        </form>

        </div>
        <div class="tar">
            <span>已有账号</span>
            <a class="zc-dl" href="{{url('login')}}">登录</a>
        </div>
        <div class="cz-clude">
            <img src="{{URL::asset('Static/images/bg14.png')}}" />
        </div>
    </div>
    <!--邮箱注册-->
    <!--手机注册-->
    <div class="shuru-area" id="tel_tab_div">
        <div class="tit-zc">
            <h3><span>手机注册</span></h3>
        </div>
        <div class="shrk-bd-zc">
            <form action="{{url('reg')}}" method="post" onSubmit=" return check_submit(this)" id="reg_form2">
                {{csrf_field()}}
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <th>手机号：</th>
                        <td><input class="zc-text" type="text" name="username" id="username2" placeholder="请输入手机号码" maxlength="11" required/></td>
                    </tr>
                    <if condition="$regis_sms_enable eq 1">
                    <tr>
                        <th>手机验证码：</th>
                        <td  class="po-re">
                            <input class="zc-text wi96" name="code" id="code" type="text" placeholder="手机验证码" required/>
                            <button type="button" class="po-ab to0" style="width: 60px;height: 35px;margin-top: 10px;margin-left: 10px" name="count_down" disabled onClick="send_sms_reg_code(this)">免费获取</button>
                        </td>
                    </tr>
                    </if>
                    <tr>
                        <th>密码：</th>
                        <td>
                            <input class="zc-text" type="password" name="password" id="password3" placeholder="请输入6-16位密码" required/>
                        </td>
                    </tr>
                    <tr>
                        <th>确认密码：</th>
                        <td><input class="zc-text" type="password" name="password2" id="password4" placeholder="请重复输入您的密码" required/></td>
                    </tr>
                    <tr>
                        <th>验证码：</th>
                        <td  class="po-re">
                            <input class="zc-text wi96" name="verify_code" id="verify_code2" type="text" placeholder="不区分大小写" required/>
                            <img class="po-ab to0" width="140" height="65" id="reflsh_code2"  src="{{url('code')}}" onClick="verify('reflsh_code2')" />
                            <a><img class="ma-le-210" src="{{URL::asset('Static/images/chg_image.png')}}" onClick="verify('reflsh_code2')" /></a>
                        </td>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <td id="pa1"><input class="checkboxs" id="agree1" name="agree" type="checkbox" value="1" required/><label for="agree">同意</label><a class="iages" href="">《帐号服务条款、隐私政策》</a></td>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <td><input type="submit" value="立即注册" class="zc-ljzc"/></td>
                    </tr>
                </table>
            </form>

        </div>
        <div class="tar">
            <span>已有账号</span>
            <a class="zc-dl" href="{{url('login')}}">登录</a>
        </div>
        <div class="cz-clude">
            <img src="{{URL::asset('Static/images/bg14.png')}}" />
        </div>
    </div>
    <!--手机注册-->
</div>
<div class="hr-60"></div>
<div class="cust-footer">
    <div class="setout po-re">
        <div class="warrant-area">
            <p>
                <a href="">《帐号服务条款、隐私政策》</a>
                <em>|</em>
                <a href="">常见问题？</a>
            </p>
        <p>Copyright © 2017   版权所有 保留一切权利 </p>
        </div>
    </div>
</div>
<script src="{{URL::asset('js/layer/layer.js')}}"></script>
<script src="{{URL::asset('js/jquery-1.10.2.min.js')}}"></script>
<script>
    flag = true;
    $(document).ready(function(){
        $("input[name='username']").blur(function () {
            var username = $(this).val();
            var re = /^(13[0-9]{9})|(15[^4,\\D][0-9]{8})|(18[0-9]{9})|(17[0-9]{9})|((\w{1,20}@\w{1,20}.[a-z]{2,5}))$/;
            var id = $(this).attr("id");
            if (re.test(username)) {
                $.post("{{url('checkUsername')}}", {
                    '_token': "{{csrf_token()}}",
                    'username': username,
                }, function (data) {
                    if (data.status) {
                        $("button[name='count_down']").attr("disabled", "disabled");
                        layer.msg(data.msg, {icon: 5});
                        flag = false;
                    } else {
                        layer.msg(data.msg, {icon: 6});
                        $("button[name='count_down']").removeAttr("disabled");
                    }
                })
            }else{
                layer.msg('请输入正确的格式', {icon: 5});
            }
        })
    });
	// 刷新普通图形验证码
    function verify(id){
        $('#'+id).attr('src',"{{url('/code?')}}"+Math.random());
    }
    function check_submit(obj){
        var username = $(obj).find('input[name="username"]').val();
        var password = $(obj).find('input[name="password"]').val();
        var password2 = $(obj).find('input[name="password2"]').val();
        var verify_code = $(obj).find('input[name="verify_code"]').val();
        var agree = $(obj).find('input[type="checkbox"]:checked').val();
        var error = '';
        if(username == ''){
            error += '用户名不能为空';
        }
        if(password == '' || password2 == ''){
            error += '密码不能为空\n';
        }
        if(password !== password2){
            error += '两次输入密码不一致\n';
        }
        if(password.length < 6){
            error += '密码长度不能低于六位\n';
        }
        if(verify_code == ''){
            error += '请输入验证码\n';
        }
        if(!agree > 0){
            error += '您没有同意注册协议\n';
        }
        if(!flag){
            error += '用户名已经被注册\n';
        }
        if(error){
            layer.msg(error, {icon: 5});// alert(error);
            return false;
        }
        return true;
    }
	// 电子邮件注册  和 手机号码注册 切换
    function reg_tab(id,id2){
        $('#'+id).addClass('ema-tab');
        $('#'+id2).removeClass('ema-tab');
        $('#'+id+'_div').show();
        $('#'+id2+'_div').hide();
    }
	 //发送手机短信验证码
    function send_sms_reg_code(val){
        var mobile = $('#tel_tab_div').find('input[name="username"]').val();
        $.ajax({
            url:"{{url('mobileCode')}}",
            data:{'mobile':mobile, '_token':'{{csrf_token()}}'},
            type:"POST",
            dataType:"Json",
            success:function(data){
                if (data.status) {
                    layer.msg(data.msg, {icon: 5});
                } else {
                    settime(val);
                    layer.msg(data.msg, {icon: 6});
                }
            }
        });
    }

     //发送邮箱验证码
    function send_smtp_reg_code(val){
        var email = $('#ema_tab_div').find('input[name="username"]').val();
        $.ajax({
            type : "POST",
            url:"{{url('emailCode')}}",
            data : {send:email, '_token':'{{csrf_token()}}'},
            dataType: "json",
            success: function(data){
                if(data.status){
                    settime(val);
                    layer.msg(data.msg, {icon: 6});
                }else{
                    layer.msg('发送失败，请刷新重试', {icon: 5});
                    return false;
                }
            }
        });
    }

    //60秒倒计时
    var countdown = 60;
    function settime(val) {
        if (countdown == 0) {
            val.removeAttribute("disabled");
            $(val).text("免费获取");
            countdown = 60;
            return ;
        } else {
            val.setAttribute("disabled", true);
            $(val).text("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function () {
            settime(val)
        }, 1000)
    }
</script>
</body>
</html>
