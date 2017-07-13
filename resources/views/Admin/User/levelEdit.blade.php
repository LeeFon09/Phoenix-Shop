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
                    <h3 class="panel-title"><i class="fa fa-list"></i> 编辑会员等级</h3>
                </div>
                <div class="panel-body ">
                    <!--表单数据-->
                    <form method="post" id="handleposition" action="{{url('admin/User/levelHandle')}}">
                        <!--通用信息-->
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="put">
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <input type="hidden" name="level_id" value="{{$data->level_id}}">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">*等级名称：</td>
                                    <td class="col-sm-4">
                                        <input type="text" class="form-control" name="level_name"  value="{{$data->level_name}}">
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                    <td class="col-sm-4">设置会员等级名称
                                    </td>
                                </tr>  
                                <tr>
                                    <td>消费额度：</td>
                                    <td >
                         				<input type="text" class="form-control" name="amount" value="{{$data->amount}}">
                                    </td>
                                    <td class="col-sm-4">设置会员等级所需要的消费额度</td>
                                </tr>
                                <tr>
                                    <td>折扣率：</td>
                                    <td>
                               			<input type="text" class="form-control" name="discount" value="{{$data->discount}}">
                                    </td>
                                    <td class="col-sm-4">折扣率单位为百分比，如输入90，表示该会员等级的用户可以以商品原价的90%购买</td>
                                </tr>  
                                <tr>
                                    <td>等级描述：</td>
                                    <td>
                             			<textarea rows="5" cols="30" name="describe">{{$data->describe}}</textarea>
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