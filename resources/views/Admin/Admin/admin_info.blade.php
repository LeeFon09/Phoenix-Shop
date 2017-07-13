@include('Admin.Public.min-header')
<div class="wrapper">
    @include('Admin.Public.breadcrumb')
    <section class="content ">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回管理员列表"><i class="fa fa-reply"></i></a>
            	<a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/1001/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 修改密码</h3>
                </div>
                <div class="panel-body ">   
                    <!--表单数据-->
                    <form method="post" id="adminHandle" action="">
                        {{csrf_field()}}
                        <!--通用信息-->
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">用户名：</td>
                                    <td class="col-sm-8">
                                        <input type="text" class="form-control" name="user_name" value="{{$admin->user_name}}" >
                                                                      
                                    </td>
                                </tr>  
                                <tr>
                                    <td>Email地址：</td>
                                    <td >
                         				<input type="text" class="form-control" name="email" value="{{$admin->email}}" >
                                                                     
                                    </td>
                                </tr>  
                                <tr>
                                    <td>登陆密码：</td>
                                    <td>
                               			<input type="password" class="form-control" name="password" value="{{$admin->password}}" >
                                    </td>
                                </tr>
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<td><input type="hidden" name="act" value="{$act}">
                                		<input type="hidden" name="admin_id" value="{{$admin->admin_id}}">
                                	</td>
                                	<td class="text-right"><input class="btn btn-primary" type="button" onclick="adsubmit()" value="保存"></td></tr>
                                </tfoot>                               
                                </table>
                        </div>                           
                    </div>              
			    	</form><!--表单数据-->
                </div>
            </div>
        </div>
    </section>
</div>
<script>
function adsubmit(){
	if($('input[name=user_name]').val() == ''){
		layer.msg('用户名不能为空！', {icon: 2,time: 1000});   //alert('少年，用户名不能为空！');
		return false;
	}
	if($('input[name=email]').val() == ''){
		layer.msg('邮箱不能为空！', {icon: 2,time: 1000});//alert('少年，邮箱不能为空！');
		return false;
	}
	if($('input[name=password]').val() == '' && '{$act}' == 'add'){
		layer.msg('密码不能为空！', {icon: 2,time: 1000});//alert('少年，密码不能为空！');
		return false;
	}
	$('#adminHandle').submit();
}
</script>
</body>
</html>
