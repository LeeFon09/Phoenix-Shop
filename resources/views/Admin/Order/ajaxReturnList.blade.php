
                    <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    </td>
                                    <td class="text-center">订单编号</td>
                                    <td class="text-center">商品名称</td>
                                    <td class="text-center">类型</td>
                                    <td class="text-center">申请日期</td>
                                    <td class="text-center">状态</td>
                                    <td class="text-center">操作</td>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($return_goods as $items)
                                    <tr>
                                        <td class="text-center"><a href="{:U('Admin/order/detail',array('order_id'=>$items['order_id']))}">{{$items->order_sn}}</a></td>
                                        <td class="text-center">{{mb_substr($items->goods_name, 0, 50)}}</td>
                                        <td class="text-center">
                                            @if($items->type == 0)
                                                退货
                                            @elseif($items->type == 1)
                                                换货
                                            @endif
                                        </td>
                                        <td class="text-center">{{date('Y-m-d H:i', $items->addtime)}}</td>
                                        <td class="text-center">
                                            @if($items->status == 0)
                                                未处理
                                            @elseif($items->type == 1)
                                                处理中
                                            @elseif($items->type == 2)
                                                已完成
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{:U('Admin/order/return_info',array('id'=>$items['id']))}" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-eye"></i></a>
                                            <a href="javascript:void(0);" onclick="if(confirm('确定要删除吗?')) location.href='{:U('Admin/order/return_del',array('id'=>$items['id']))}';" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-6 text-left"></div>
                        <div class="col-sm-6 text-right">{$page}</div>
                    </div>
<script>
    $(".pagination  a").click(function(){
        var page = $(this).data('p');
        ajax_get_table('search-form2',page);
    });
</script>