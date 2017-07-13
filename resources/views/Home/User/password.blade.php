<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>密码-{$tpshop_config['shop_info_store_title']}</title>
    <meta http-equiv="keywords" content="{$tpshop_config['shop_info_store_keyword']}" />
    <meta name="description" content="{$tpshop_config['shop_info_store_desc']}" />
    <link rel="stylesheet" href="{{asset('Static/css/index.css')}}" type="text/css">
    <script src="{{asset('Static/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{asset('Static/js/slider.js')}}"></script>
	<script src="{{asset('js/global.js')}}"></script>
    <script src="{{asset('js/pc_common.js')}}"></script>
    <script src="{{asset('js/layer/layer.js')}}"></script>
</head>

<body style="background:RGB(242,242,242)">
<div class="uc">
    <div class="uc_head">
        <div class="uc_head_middle">
            <div class="uc_head_middle_left">修改密码</div>
            <div class="uc_head_middle_right"><a class="logout_c" href="/">首页</a> | <a class="logout_c" href="javascript:history.back(-1)">返回</a></div>
        </div>
    </div>
    <div class="uc_body pa-to-30">
        <div class="uc_body_form">
            <form action="" method="post" id="form">
                <table class="changeAccount_theme">
                    <tbody>
                    <tr class="tr_height">
                        <td class="cl_left fo-si-12" align="right"><label class="fo-si-12">原密码：</label></td>
                        <td class="cl_middle">
                            <input type="password" class="imput_text text_width foce" id="old_password" name="old_password">

                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr class="tr_height">
                        <td class="cl_left fo-si-12" align="right"><label class="fo-si-12">新密码：</label></td>
                        <td class="cl_middle">
                            <input type="password" class="imput_text text_width foce" id="new_password" name="new_password" >
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr class="tr_height">
                        <td class="cl_left fo-si-12" align="right"><label class="fo-si-12">确认密码：</label></td>
                        <td class="cl_middle">
                            <input type="password" class="imput_text text_width foce" id="confirm_password" name="confirm_password">
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr class="tr_height">
                        <td valign="middle" align="center" colspan="3" style="height:120px;">
                            <input type="submit" value="提交" class="btn_midefy" id="btn_next">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    $("#btn_next").click(function(){
        var old_pass = $("#old_password").val();
        var new_pass = $("#new_password").val();
        var confirm_pass = $("#confirm_password").val();

        if(!old_pass || !new_pass || !confirm_pass){
            layer.alert('密码不能为空', {icon: 2});//alert('原邮箱格式错误');
            return false;
        }

        if(new_pass !== confirm_pass){
            layer.alert('新密码与确认密码必须相同', {icon: 2});//alert('原邮箱格式错误');
            return false;
        }
        var data = $("form").serialize();
//        alert(data);
        $.post("{{url('home/user/ajaxPassword')}}", {"_token":"{{csrf_token()}}", "data":data}, function(data){
            if(data == 1){
                layer.alert('密码修改成功', {icon: 2});//alert('原邮箱格式错误');
                history.back();
            }else{
                layer.alert(data, {icon: 2});//alert('原邮箱格式错误');
                return false;
            }
        });
        return false;
    });



</script>
</body>
</html>
