{{--<include file="Public/min-header" />--}}
@include('Admin.Public.min-header')
<div class="wrapper">
  {{--<include file="Public/breadcrumb"/>--}}
    @include('Admin.Public.breadcrumb')
    <section class="content ">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
                <a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/1005/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if(in_array('user',session('url')) || session('admin')->admin_id == '1')
                    <h3 class="panel-title"><i class="fa fa-list"></i> 添加会员等级</h3>
                    @endif
                </div>
                <div class="panel-body ">
                    @if(count($errors)>0)
                        <div>
                            @foreach($errors as $error)
                                <span style="background-color: red;">{{$error}}</span>
                            @endforeach
                        </div>
                        <br>
                    @endif
                    <!--表单数据-->
                    <form method="post" id="handleposition" action="{{url('admin/User/levelHandle')}}">
                        <!--通用信息-->
                        {{csrf_field()}}
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">*等级名称：</td>
                                    <td class="col-sm-4">
                                        <input type="text" class="form-control" name="level_name"  >
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                    <td class="col-sm-4">设置会员等级名称
                                    </td>
                                </tr>  
                                <tr>
                                    <td>消费额度：</td>
                                    <td >
                         				<input type="text" class="form-control" name="amount" >
                                    </td>
                                    <td class="col-sm-4">设置会员等级所需要的消费额度</td>
                                </tr>
                                <tr>
                                    <td>折扣率：</td>
                                    <td>
                               			<input type="text" class="form-control" name="discount" >
                                    </td>
                                    <td class="col-sm-4">折扣率单位为百分比，如输入90，表示该会员等级的用户可以以商品原价的90%购买</td>
                                </tr>  
                                <tr>
                                    <td>等级描述：</td>
                                    <td>
                             			<textarea rows="5" cols="30" name="describe"></textarea>
                                    </td>
                                    <td class="col-sm-4">会员等级描述信息</td>
                                </tr>                              
                                </tbody> 
                                <tfoot>
                                	<tr>
                                        <td></td>
                                        <td class="col-sm-4"></td>
                                        <td class="text-right"><input class="btn btn-primary" type="buuton" onclick="adsubmit()" value="保存"></td>
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
<script>
function adsubmit(){
	$('#handleposition').submit();
}
</script>
</body>
</html>
