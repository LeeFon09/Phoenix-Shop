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
                    <h3 class="panel-title"><i class="fa fa-list"></i> 添加权限</h3>
                </div>
                <div class="panel-body ">   
                    <!--表单数据-->
                    <form method="post" id="adminHandle" action="{{ url('admin/Admin/access') }}">
                        {{csrf_field()}}
                        <!--通用信息-->
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">权限名称：</td>
                                    <td class="col-sm-8">
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" >
                                                                      
                                    </td>
                                </tr>  
                                <tr>
                                    <td>权限类型：</td>
                                    <td >
                                        <select name="type">
                                            <option value="admin">管理员管理</option>
                                            <option value="user">会员管理</option>
                                            <option value="goods">商品管理</option>
                                            <option value="access">权限管理</option>
                                            <option value="role">角色管理</option>
                                            <option value="order">订单管理</option>
                                            <option value="ad">广告管理</option>
                                            <option value="content">内容管理</option>
                                            <option value="comment">商品评价管理</option>
                                        </select>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="col-sm-2">描述：</td>
                                    <td class="col-sm-8">
                                        <textarea name="desc" cols="30" rows="10"></textarea>
                                    </td>
                                </tr>
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
