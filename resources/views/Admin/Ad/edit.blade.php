{{--<include file="Public/min-header"/>--}}
@include('admin.Public.min-header')
<link href="{{URL::asset('plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css" />
<script src="{{URL::asset('plugins/daterangepicker/moment.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<div class="wrapper">
    {{--<include file="Public/breadcrumb"/>--}}
    @include('admin.Public.breadcrumb')
    <section class="content ">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
				<a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/1012/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 编辑广告</h3>
                </div>
                <div class="panel-body ">   
                    <!--表单数据-->
                    <form method="post" id="handleposition" action="" enctype="multipart/form-data">
                        <!--通用信息-->
                        {{csrf_field()}}
                        <input type="hidden" name="ad_id" value="{{$list->ad_id}}">
                    <div class="tab-content col-md-10">                 	  
                        <div class="tab-pane active" id="tab_tongyong">                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td class="col-sm-2">广告名称：</td>
                                    <td class="col-sm-8">
                                        <input type="text" class="form-control" name="ad_name" value="{{$list->ad_name}}" >
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr>           
                                <tr>
                                    <td>广告类型：</td>
                                    <td>
                                        <select name="media_type" class="input-sm">
                                             <option value="0" @if($list->media_type==0) selected @endif>图片</option>
                                             <option value="1" @if($list->media_type==1) selected @endif>flash</option>
                                        </select>                                
                                    </td>
                                </tr>  
                                <tr>
                                    <td>广告位置：</td>
                                    <td>
                                        <select name="pid" class="input-sm">
                                            @foreach($position as $item)
                                                   <option value="{{$item->position_id}}" @if($list->pid == $item->position_id) selected @endif>{{$item->position_name}}</option>
                                            @endforeach
                                        </select>      
                                    </td>
                                </tr>  
                                <tr>
                                    <td>开始日期：</td>
                                    <td>
                                        <fieldset>
                                        <div class="control-group">
                                                <div class="controls">
                                                        <div class="input-prepend input-group">
                                                                <span class="add-on input-group-addon">
                                                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                                </span>
                                                                <input type="text" style="width: 300px" name="begin" id="start_time" class="form-control" value="{{date('Y-m-d', $list->start_time)}}">
                                                        </div>
                                                </div>
                                        </div>
                                        </fieldset>
                                    </td>
                                </tr>  
                                <tr>
                                    <td>结束时间：</td>
                                    <td>
                                        <fieldset>
                                            <div class="control-group">
                                                    <div class="controls">
                                                            <div class="input-prepend input-group">
                                                                    <span class="add-on input-group-addon">
                                                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                                                    </span>
                                                                    <input type="text" style="width: 300px" name="end" id="end_time" class="form-control" value="{{date('Y-m-d', $list->end_time)}}">
                                                            </div>
                                                    </div>
                                            </div>
                                            </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <td>广告链接：</td>
                                    <td>
                                        <input type="text" class="form-control" name="ad_link" value="{{$list->ad_link}}">
                                    </td>
                                </tr> 
                                <tr>
                                    <td>广告图片：</td>
                                    <td>
                      					<div class="col-sm-3">
                      						<input type="file" class="form-control btn btn-default" style="width:350px;margin-left:-15px;" name="ad_code" id="ad_code" value="{{$list->ad_code}}">
                      					</div>
                      					{{--<div class="col-sm-3">--}}
                                        	{{--<input type="button" class="btn btn-default"   value="上传图片"/>--}}
                                        {{--</div>--}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>缩略图片：</td>
                                    <td>
                                        <img alt="" src="{{$list->ad_code}}" width="80px" height="50px">
                                    </td>
                                </tr>
                                <tr>
                                	<td>背景颜色：</td>
                                	<td> <input class="form-control" name="bgcolor" type="color" style="width:200px;" value="{{$list->bgcolor}}"/> </td>
                                </tr>
                                <tr>
                                    <td>默认排序：</td>
                                    <td>
                                        <input type="text" class="input-sm" name="orderby" value="{{$list->orderby}}">
                                    </td>
                                </tr>                                
                                </tbody> 
                                <tfoot>
                                	<tr>
                                        <td></td>
                                        <td class="text-right"><input class="btn btn-primary" type="button" onclick="adsubmit()" value="保存"></td>
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

$(document).ready(function() {

	$('#start_time').daterangepicker({
		format:"YYYY-MM-DD",
		singleDatePicker: true,
		showDropdowns: true,
		minDate:'2016-01-01',
		maxDate:'2030-01-01',
		startDate:'2016-01-01',
	    locale : {
            applyLabel : '确定',
            cancelLabel : '取消',
            fromLabel : '起始时间',
            toLabel : '结束时间',
            customRangeLabel : '自定义',
            daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
            monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月' ],
            firstDay : 1
        }
	});
	
	$('#end_time').daterangepicker({
		format:"YYYY-MM-DD",
		singleDatePicker: true,
		showDropdowns: true,
		minDate:'2016-01-01',
		maxDate:'2030-01-01',
		startDate:'2016-01-01',
		/*
        startDate: moment().startOf('day'),
        endDate: moment(),
        minDate: '01/01/2014',    //最小时间
        maxDate : moment(), //最大时间
        dateLimit : {
            days : 30
        }, //起止时间的最大间隔
        showDropdowns : true,
        showWeekNumbers : false, //是否显示第几周
        timePicker : true, //是否显示小时和分钟
        timePickerIncrement : 60, //时间的增量，单位为分钟
        timePicker12Hour : false, //是否使用12小时制来显示时间
        ranges : {
            '最近1小时': [moment().subtract('hours',1), moment()],
            '今日': [moment().startOf('day'), moment()],
            '昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],
            '最近7日': [moment().subtract('days', 6), moment()],
            '最近30日': [moment().subtract('days', 29), moment()]
        },
        opens : 'right', //日期选择框的弹出位置
        buttonClasses : [ 'btn btn-default' ],
        applyClass : 'btn-small btn-primary blue',
        cancelClass : 'btn-small',
        format : 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
        separator : ' to ',
        */
	    locale : {
            applyLabel : '确定',
            cancelLabel : '取消',
            fromLabel : '起始时间',
            toLabel : '结束时间',
            customRangeLabel : '自定义',
            daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
            monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月' ],
            firstDay : 1
        }
	});

});

</script>
 </body>
 </html>