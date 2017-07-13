@include('Admin.Public.min-header')
<div class="wrapper">
    @include('Admin.Public.breadcrumb')
    <section class="content ">
         @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回管理员列表"><i class="fa fa-reply"></i></a>
            	<a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/1001/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 编辑管理员</h3>
                </div>
                <div class="panel-body ">   
                    <!--表单数据-->
                    <form method="post" id="adminHandle" action="{{ url('admin/Admin/update') }}">
                        {{csrf_field()}}
                        <!--通用信息-->
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                @foreach($admin as $v)
                                <tr>
                                    <td class="col-sm-2">用户名：</td>
                                    <td class="col-sm-8">
                                        <input type="text" class="form-control" name="user_name" value="{{ $v->user_name }}" >
                                                                      
                                    </td>
                                </tr>  
                                <tr>
                                    <td>Email地址：</td>
                                    <td >
                         				<input type="text" class="form-control" name="email" value="{{ $v->email }}" >
                                                                     
                                    </td>
                                </tr>  
                                <tr>
                                    <td>登陆密码：</td>
                                    <td>
                               			<input type="password" class="form-control" name="password">
                                    </td>
                                </tr>
                                <tr>
                                    <td>确认密码：</td>
                                    <td>
                                        <input type="password" class="form-control" name="repassword">
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>所属角色：</td>
                                    <td>
                             			<select name="role_name">
                                            <option value="">选择角色</option>
	                               			@foreach($role as $r)
	                         					<option value="{{$r->name}}">{{ $r->name }}</option>
	                         				@endforeach                 
                         				</select>    
                                    </td>
                                </tr>
                                <input type="hidden" name="admin_id" value="{{$v->admin_id}}">  
                                @endforeach
                                </tbody> 
                                <tfoot>
                                <tr>
                                	<td class="text-right" colspan="2"><input class="btn btn-primary" type="submit" value="保存">
                                    </td>
                                </tr>
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

</body>
</html>
