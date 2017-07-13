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
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 添加链接</h3>
                </div>
                <div class="panel-body ">   
                    <!--表单数据-->
                    <form method="post" id="handleposition" action="{{url('admin/Article/linkHandle')}}" enctype="multipart/form-data">
                        <!--通用信息-->
                        {{csrf_field()}}
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">链接名称：</td>
                                    <td class="col-sm-4">
                                        <input type="text" class="form-control" name="link_name" >
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                    <td class="col-sm-4">请填写友情链接的名称
                                    </td>
                                </tr>  
                                <tr>
                                    <td>是否显示：</td>
                                    <td >
                         				<input type="radio" class="" name="is_show" value="1" checked="checked">开启
                      					<input type="radio" class="" name="is_show" value="0" >关闭
                                    </td>
                                    <td class="col-sm-4">是否在前台显示</td>
                                </tr>  
                                <tr>
                                    <td>是否新窗口打开：</td>
                                    <td>
                               			<input type="radio" class="" name="target" value="1"   checked="checked">开启
                      					<input type="radio" class="" name="target" value="0"  >关闭
                                    </td>
                                    <td class="col-sm-4">点击链接是否在新窗口打开</td>
                                </tr>  
                                <tr>
                                    <td>链接LOGO：</td>
                                    <td>
                             			<input type="file" class="button" name="link_logo" id="link_logo"  value="上传图片"/>
                                    </td>
                                    <td class="col-sm-4">选择友情链接的LOGO</td>
                                </tr>  
                                <tr>
                                    <td>链接地址：</td>
                                    <td>
                      					<input type="text" class="form-control" name="link_url"  >
                                    </td>
                                    <td class="col-sm-4">友情链接跳转地址</td>
                                </tr>
                                <tr>
                                    <td>排序：</td>
                                    <td>
                      					<input type="text" class="form-control" name="orderby" >
                                    </td>
                                    <td class="col-sm-4">请填写自然数，友情链接会根据排序进行由小到大排列显示</td>
                                </tr>                                 
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<td></td>
                                	<td class="col-sm-4"></td>
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
	$('#handleposition').submit();
}
</script>
</body>
</html>