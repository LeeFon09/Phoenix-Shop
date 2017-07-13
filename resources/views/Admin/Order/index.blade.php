{{--<include file="Public/min-header"/>--}}
@include('Admin.Public.min-header')
<link href="{{URL::asset('plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css" />
<script src="{{URL::asset('plugins/daterangepicker/moment.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<div class="wrapper">
    {{--<include file="Public/breadcrumb"/>--}}
    @include('Admin.Public.breadcrumb')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i>订单列表</h3>
                </div>
                <div class="panel-body">
                    <div class="navbar navbar-default">
                        <form action="" id="search-form2" class="navbar-form form-inline" method="post">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label class="control-label" for="input-order-id">处理状态</label>
                                <select class="form-control" id="handle_status" name="handle_status">
                                    <option value="-1" >--请选择--</option>
                                    <option value="0"  >未付款</option>
                                    <option value="2"  >已付款</option>
                                    <option value="4"  >已发货</option>
                                    <option value="6"  >已收货</option>
                                    <option value="8"  >已评价</option>
                                    <option value="10"  >交易完结</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="input-order-id">是否有效</label>
                                <select class="form-control" id="is_valid" name="is_valid">
                                    <option value="-1" >--请选择--</option>
                                    <option value="0"  >无效</option>
                                    <option value="1"  >有效</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="input-group margin">
                                    <div class="input-group-addon">
                                        下单时间&nbsp;<i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" name="commit_time" id="start_time" value="@if(!empty($input) ) {{$input['commit_time']}} @endif">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="button-filter search-order" class="btn btn-primary pull-right"><i class="fa fa-search"></i> 筛选</button>
                            </div>
                            @if(!empty($input) && $input['handle_status']==2)
                                <a href="javascript:void(0)" class="btn btn-success pull-right" onclick="orderHandle(this)">发货</a>
                            @endif
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td class="text-left" width="5%">
                                    <label>
                                        <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">ID
                                    </label>
                                </td>
                                <td class="text-left">订单编号</td>
                                <td class="text-left">收货人</td>
                                <td class="text-left">应付金额</td>
                                <td class="text-left">总金额</td>
                                <td class="text-left">订单状态</td>
                                <td class="text-left">处理状态</td>
                                <td class="text-left">是否有效</td>
                                <td class="text-left">下单时间</td>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr align="center">
                                        <td class="text-center">
                                            <label><input type="checkbox" name="selected[]" value="{{$order->order_id}}">{{$order->order_id}}</label>
                                        </td>
                                        <td class="text-center">{{$order->order_sn}}</td>
                                        <td class="text-center">{{$order->consignee}}</td>
                                        <td class="text-center">{{$order->order_amount}}</td>
                                        <td class="text-center">{{$order->total_amount}}</td>
                                        <td class="text-center">
                                            @if($order->order_status == 1)
                                                待付款
                                            @elseif($order->order_status == 3)
                                                待发货
                                            @elseif($order->order_status == 5)
                                                待收货
                                            @elseif($order->order_status == 7)
                                                待评价
                                            @elseif($order->order_status == 9)
                                                交易成功
                                            @else
                                                删除订单
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($order->handle_status == 0)
                                                未付款
                                            @elseif($order->handle_status == 2)
                                                已付款
                                            @elseif($order->handle_status == 4)
                                                已发货
                                            @elseif($order->handle_status == 6)
                                                已收货
                                            @elseif($order->handle_status == 8)
                                                已评价
                                            @else
                                                交易完结
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <img width="20" height="20" onclick="changeStatus(this)" data-id="{{$order->order_id}}" data-status="{{$order->is_valid}}" data-url="{{url('admin/Order/changeStatus')}}" src="{{$order->is_valid==1 ? URL::asset('images/yes.png') : URL::asset('images/cancel.png')}}"/>
                                        </td>
                                        <td class="text-center">{{$order->commit_time}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if(!empty($pageObj))
                            <div class="pull-right">
                                {!! $pageObj->show() !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {

        $('#start_time').daterangepicker({
            format:"YYYY-MM-DD",
            singleDatePicker: false,
            showDropdowns: true,
            minDate:'2016-01-01',
            maxDate:'2030-01-01',
            startDate:'2016-01-01',
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                '今天': [moment(), moment()],
                '昨天': [moment().subtract('days', 1), moment().subtract('days', 1)],
                '最近7天': [moment().subtract('days', 6), moment()],
                '最近30天': [moment().subtract('days', 29), moment()],
                '上一个月': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            opens: 'right',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
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


    function orderHandle(obj)
    {
        layer.confirm('您确定要继续此操作么？', {
            btn: ['确定','手误'] //按钮
        }, function(){
            var obj = $("input[name*='selected']");
            var url = "{{url('admin/Order/orderHandle')}}";
            if(obj.is(":checked")) {
                var check_val = [];
                for (var k in obj) {
                    if (obj[k].checked)
                        check_val.push(obj[k].value);
                }
                $.post(url, {
                    '_token': '{{csrf_token()}}',
                    'order_ids': check_val,
                }, function (data) {
                    if (data.status) {
                        layer.msg(data.msg, {icon: 6});
                        location.href = location.href;
                    } else {
                        layer.msg('操作失败！', {icon: 5});
                    }
                });
            }
        }, function(){
            layer.msg('注意点哈', {
                time: 3000, //3s后自动关闭
                btn: ['好咧']
            });
        });
    }

    function changeStatus(obj)
    {
        $.ajax({
            type: 'post',
            url: $(obj).attr('data-url'),
            data: {
                'order_id': $(obj).attr('data-id'),
                'is_valid': $(obj).attr('data-status'),
                '_token': '{{csrf_token()}}',
            },
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    layer.msg(data.msg, {icon: 6});
                    location.href = location.href;
                } else {
                    layer.msg(data.msg, {icon: 5});
                }
            }
        })
    }

</script>