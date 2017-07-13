{{--<include file="Public/min-header"/>--}}
@include('Admin.Public.min-header')
<div class="wrapper">
  {{--<include file="Public/breadcrumb"/>--}}
    @include('Admin.Public.breadcrumb')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i>退货单列表</h3>
                </div>
                <div class="panel-body">
                    <div class="navbar navbar-default">
                            <form action="" id="search-form2" class="navbar-form form-inline" method="post">
                                {{csrf_field()}}
				                <div class="form-group">
                                  <label class="control-label" for="input-order-id">状态</label>
                                  <select class="form-control" id="status" name="status">
                                    <option value="0" >未处理</option>
                                    <option value="1" >处理中</option>
                                    <option value="2" >已完成</option>
                                   </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="input-order-id">订单 编号</label>
                                    <div class="input-group">
                                        <input type="text" name="order_sn" value="" placeholder="订单 编号" id="input-order-id" class="form-control">
                                        <!--<span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>-->
                                    </div>
                                </div>
                                <button type="submit"  id="button-filter search-order" class="btn btn-primary "><i class="fa fa-search"></i> 筛选</button>
                            </form>
                    </div>
                    {{--<div id="ajax_return">--}}

                    {{--</div>--}}
                    <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td class="text-center">订单编号</td>
                                    <td class="text-center">商品名称</td>
                                    <td class="text-center">类型</td>
                                    <td class="text-center">申请日期</td>
                                    <td class="text-center">状态</td>
                                    <td class="text-center">操作</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($return_goods as $return_good)
                                    <tr>
                                        <td class="text-center"><a href="/index.php/Admin/order/detail/order_id/177">{{$return_good->order_sn}}</a></td>
                                        <td class="text-center">{{mb_substr($return_good->goods_name,0,40)}}</td>
                                        <td class="text-center">
                                            @if($return_good->type == 0)
                                                退货
                                            @elseif($return_good->type ==1)
                                                换货
                                            @endif
                                        </td>
                                        <td class="text-center">{{date('Y-m-d H:i', $return_good->addtime)}}</td>
                                        <td class="text-center">
                                            @if($return_good->status == 0)
                                                未处理
                                            @elseif($return_good->status == 1)
                                                处理中
                                            @elseif($return_good->status == 2)
                                                已完成
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{url('admin/Order/return_info/'.$return_good->id)}}" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-eye"></i></a>
                                            <a class="btn btn-danger" onclick="delReturn(this)" data-url="{{url('admin/Order/returnDel')}}" data-id="{{$return_good->id}}"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
 
                </div>
            </div>
        </div>        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>

    function delReturn(obj){
        if(confirm('确认删除')){
            $.ajax({
                type : 'post',
                url : $(obj).attr('data-url'),
                data : {id:$(obj).attr('data-id'),'_token':'{{csrf_token()}}'},
                dataType : 'json',
                success : function(data){
                    if (data.status) {
                        layer.msg(data.msg, {icon: 6});
                        location.href = location.href;
                    } else {
                        layer.msg(data.msg, {icon: 5});
                    }
                }
            });
        }
        return false;
    }

    $(document).ready(function(){
        ajax_get_table('search-form2',1);

    });
    // ajax 抓取页面
    function ajax_get_table(tab,page){
        cur_page = page; //当前页面 保存为全局变量
            $.ajax({
                type : "POST",
                url:"/index.php/Admin/order/ajax_return_list/p/"+page,//+tab,
                data : $('#'+tab).serialize(),// 你的formid
                success: function(data){
                    $("#ajax_return").html('');
                    $("#ajax_return").append(data);
                }
            });
    }

    // 点击排序
    function sort_list(field)
    {
        $("input[name='order_by']").val(field);
        var v = $("input[name='sort']").val() == 'desc' ? 'asc' : 'desc';
        $("input[name='sort']").val(v);
        ajax_get_table('search-form2',cur_page);
    }
</script>
</body>
</html>