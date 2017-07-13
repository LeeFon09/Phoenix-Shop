<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>个人信息-{$tpshop_config['shop_info_store_title']}</title>
    <meta http-equiv="keywords" content="{$tpshop_config['shop_info_store_keyword']}" />
    <meta name="description" content="{$tpshop_config['shop_info_store_desc']}" />
    <link rel="stylesheet" href="{{URL::asset('Static/css/index.css')}}" type="text/css">
    <link rel="stylesheet" href="{{URL::asset('Static/css/page.css')}}" type="text/css">
    <script src="{{URL::asset('Static/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{URL::asset('Static/js/slider.js')}}"></script>
    <style>.pagination{margin-right: 16px}</style>
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
        <div class="xgzl-w" style="overflow: hidden;">
            <form action="" method="post" enctype="multipart/form-data" name="distribut_form" id="distribut_form">
                <dl>
                    <dd class="te-al po-re wi230 fl">&nbsp;
                        
                    </dd>
                    <dd class="fl ta-tldf">
                        <table style="width:700px;">
                            <tbody>
                            <tr>
                                <td class="cl_left">提现金额：</td>
                                <td>
                                    <div class="dv_cell_left">
	                                    <input type="text" id="money" name="money"  class="imput_text vam" placeholder="最少提现额度{$tpshop_config['distribut_min']}" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"/>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="cl_left">银行名称：</td>
                                <td>
                                    <div class="dv_cell_left">
           								<input type="text" id="bank_name" name="bank_name" class="imput_text vam" placeholder="如:支付宝,农业银行,工商银行等..."/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="cl_left">收款账号：</td>
                                <td>
                                    <div class="dv_cell_left">
                                        <input type="text" id="account_bank" name="account_bank" class="imput_text vam" placeholder="如:支付宝账号,建设银行账号"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="cl_left">开户名：</td>
                                <td>
                                    <div class="dv_cell_left">
                                        <input type="text" id="account_name" name="account_name" class="imput_text vam" placeholder="开户人姓名"/>
                                    </div>
                                </td>
                            </tr>                            
                            <tr>
                                <td class="cl_left">验证码：</td>
                                <td>
                                    <div class="dv_cell_left">
                                        <input type="text" name="verify_code" class="imput_text vam" id="verify_code" placeholder="请输入下面的验证码" style="width:200px;" />
                                        <img class="yzm_code" id="verify_code_img" width="100" height="30" src="{:U('User/verify',array('type'=>'withdrawals'))}"  onclick="verify(this)" />

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="left">
                                    <input type="button" onClick="checkSubmit();" value="提交" class="btn_midefy" id="btn_midefy">
                                </td>
                            </tr>
                            </tbody></table>
                    </dd>
                </dl>
            </form>
        </div>
        <div class="cash_num mt20">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th>编号</th>
					<th>申请日期</th>                    
					<th>金额</th>
					<th>状态</th>
				</tr>
                <foreach name="list" item="v" > 
				<tr>
					<td>{$v['id']}</td>
					<td>{$v['create_time']|date="Y-m-d",###}</td>
					<td>{$v['money']}</td>                    
					<td>
                    <if condition="$v[status] eq 0">申请中</if>
                    <if condition="$v[status] eq 1">申请成功</if>
                    <if condition="$v[status] eq 2">申请失败</if>                    
                    </td>
				</tr>
                </foreach>               
			</table>
		</div>
        
            {$show}
        
    </div>
</div>
<div class="he80"></div>
<!--------footer-开始-------------->
{{--<include file="Public/footer2" />--}}
@include('Home.Public.footer2')
<!--------footer-结束-------------->
</body>
<script>
// 表单验证提交
function checkSubmit(){
	var money = $.trim($('#money').val());
	var bank_name = $.trim($('#bank_name').val());
	var account_bank = $.trim($('#account_bank').val());
	var account_name = $.trim($('#account_name').val());
	var verify_code = $.trim($('#verify_code').val());
	
	if(money == '')
	{
		alert('提现金额必填');
		return false;
	}
	if(money < {$tpshop_config['distribut_min']} || money > {$user['user_money']})
	{
		//alert("每次最少提现额度{$tpshop_config['distribut_min']},你的账户余额{$user['user_money']}");
		//return false;
	}
			
	if(bank_name == '')
	{
		alert('银行名称必填');
		return false;
	}
	if(account_bank == '')
	{
		alert('收款账号必填');
		return false;
	}
	if(account_name == '')
	{
		alert('开户名必填');
		return false;
	}
	if(verify_code == '')
	{
		alert('验证码必填');
		return false;
	}
	$('#distribut_form').submit();
}

// 验证码切换
function verify(){
   $('#verify_code_img').attr('src','/index.php?m=Home&c=User&a=verify&type=withdrawals&r='+Math.random());
}

 
</script>	
</html>