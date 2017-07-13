{{--<include file="Public/min-header" />--}}
@include('admin.Public.min-header')
<div class="wrapper">
	{{--<include file="Public/breadcrumb"/>		--}}
    @include('admin.Public.breadcrumb')
    <section class="content ">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
             	<a href="javascript:history.go(-1)" class="btn btn-default"><i class="fa fa-reply"></i> 返回</a>
            	<a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/166/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 添加广告位</h3>
                </div>
                <div class="panel-body ">   
                    <!--表单数据-->
                    <form method="post" id="handleposition" action="{{url('admin/Ad/positionHandle')}}">
                        <!--通用信息-->
                        {{csrf_field()}}
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">广告位名称：</td>
                                    <td class="col-sm-8">
                                        <input type="text" class="form-control" name="position_name" >
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr>  
                                <tr>
                                    <td>广告位宽度：</td>
                                    <td >
                         				<input type="text" class="input-sm" name="ad_width" > px
                                        <span id="err_type_id" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr>  
                                <tr>
                                    <td>广告位高度：</td>
                                    <td>
                               			<input type="text" class="input-sm" name="ad_height" > px
                                    </td>
                                </tr>  
                                <tr>
                                    <td>广告位描述：</td>
                                    <td>
                             			<input type="text" class="form-control" name="position_desc" >
                                    </td>
                                </tr>  
                                <tr>
                                    <td>是否启用：</td>
                                    <td>
                                        <label><input type="radio" name="is_open" value="1"  > 开启</label>
                                        <label><input type="radio" name="is_open" value="0"  checked="checked"> 关闭</label>
                                    </td>
                                </tr>                                
                                </tbody> 
                                <tfoot>
                                	<tr>
                                	<td></td>
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