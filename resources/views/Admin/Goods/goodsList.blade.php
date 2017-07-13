@include('Admin/Public/min-header')
<div class="wrapper">
    @include('Admin/Public/breadcrumb')
    <style>
    #search-form > .form-group {
        margin-left: 10px;
    }</style>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (session('info'))
                        <div class="alert alert-success">
                            {{ session('info') }}
                        </div>
                    @endif
                    <h3 class="panel-title"><i class="fa fa-list"></i> 商品列表</h3>
                </div>
                <div class="panel-body">
                    <div class="navbar navbar-default">

                        @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                            <button type="button" onclick="location.href='{{ url('admin/Goods/goodsList/create') }}'" class="btn btn-primary pull-right">
                                <i class="fa fa-plus"></i>添加新商品
                            </button>
                        @endif
                        <form action="{{ url('admin/Goods/goodsList') }}" id="search-form2" class="navbar-form form-inline">
                            <div class="form-group">
                                <select name="cat_level_1" id="cat_level_1" class="form-control">
                                    <option value="">请选择一级分类</option>
                                @foreach($type as $t)
                                    <option value="{{ $t->id }}"> {{ $t->name }}</option>
                                @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="cat_level_2" id="cat_level_2" class="form-control">
                                    <option value="">请选择二级分类</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="cat_id" id="cat_id" class="form-control">
                                    <option value="">请选择三级分类</option>
                                </select>
                            </div>
                            <br/>
                            <div class="form-group">
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value="">所有品牌</option>
                                    @foreach($brand as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <select name="is_on_sale" id="is_on_sale" class="form-control">
                                    <option value="">全部</option>
                                    <option value="1">上架</option>
                                    <option value="0">下架</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="intro" class="form-control">
                                    <option value="">全部</option>
                                    <option value="is_new">新品</option>
                                    <option value="is_recommend">推荐</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="input-order-id">关键词</label>
                                <div class="input-group">
                                    <input type="text" name="keyword" value="" placeholder="搜索词" id="input-order-id" class="form-control">
                                </div>
                            </div>

                            <button type="submit" id="button-filter search-order" class="btn btn-primary">
                                <i class="fa fa-search"></i> 筛选
                            </button>

                        </form>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 1px;" class="text-center"></th>
                            <th class="text-center">ID</th>
                            <th class="text-center" width="300">商品名称</th>
                            <th class="text-center">分类</th>
                            <th class="text-center">价格</th>
                            <th class="text-center">库存</th>
                            <th class="text-center">上架</th>
                            <th class="text-center">推荐</th>
                            <th class="text-center">新品</th>
                            <th class="text-center">热卖</th>
                            @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                                <th class="text-center">操作</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($goods as $v)
                            <tr>
                                <td class="text-center">
                                    <input type="hidden" name="shipping_code[]" value="flat.flat">
                                </td>
                                <td class="text-center">{{ $v->goods_id }}</td>
                                <td class="text-center">{{ $v->goods_name }}</td>
                                <td class="text-center">{{ $v->cat_name }}</td>
                                <td class="text-center">{{ $v->market_price }}</td>
                                <td class="text-center">{{ $v->store_count }}</td>
                                <td class="text-center">
                                    <img width="20" height="20" onclick="changeStatus(this)" data="1" data-id="{{$v->goods_id}}" data-status="{{$v->is_on_sale}}" data-url="{{url('admin/Goods/goodsList/changeStatus')}}" src="{{$v->is_on_sale == 1 ? url('images/yes.png') : url('images/cancel.png')}}">
                                </td>
                                <td class="text-center">
                                    <img width="20" height="20" onclick="changeStatus(this)" data="2" data-id="{{$v->goods_id}}" data-status="{{$v->is_recommend}}" data-url="{{url('admin/Goods/goodsList/changeStatus')}}" src="{{$v->is_recommend == 1 ? url('images/yes.png') : url('images/cancel.png')}}">
                                </td>
                                <td class="text-center">
                                    <img width="20" height="20" onclick="changeStatus(this)" data="3" data-id="{{$v->goods_id}}" data-status="{{$v->is_new}}" data-url="{{url('admin/Goods/goodsList/changeStatus')}}" src="{{$v->is_new == 1 ? url('images/yes.png') : url('images/cancel.png') }}">
                                </td>
                                <td class="text-center">
                                    <img width="20" height="20" onclick="changeStatus(this)" data="4" data-id="{{$v->goods_id}}" data-status="{{$v->is_hot}}" data-url="{{url('admin/Goods/goodsList/changeStatus')}}" src="{{$v->is_hot == 1 ? url('images/yes.png') : url('images/cancel.png') }}">
                                </td>
                                @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                                    <td class="text-center">
                                        <a target="_blank" href="{{ url('home/goods/goodsinfo/'.$v->goods_id) }}" class="btn btn-info" title="查看详情"><i class="fa fa-eye"></i></a>
                                        <a href="{{ url('admin/Goods/goodsList/'.$v->goods_id.'/edit') }}" class="btn btn-primary" title="编辑"><i class="fa fa-pencil"></i></a>
                                        <a href="{{ url('admin/Goods/goodsList/del/'.$v->goods_id) }}" class="btn btn-danger" title="删除"><i class="fa fa-trash-o"></i></a>

                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div align="right">{!! $pageObj->show() !!}</div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
<script type="text/javascript">
$(function(){
    //获得二级分类
    $('#cat_level_1').change(function () {
        //清除商品分类列表
        $('#cat_level_2').empty();
//        $('#cat_id_3 option[value!="0"]').remove();
        //获取上级分类的ID
        var value = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{url('admin/Goods/categoryList/getCategory')}}",
            data: {'value': value, '_token': '{{csrf_token()}}'},
            dataType: 'json',
            success: function (data) {
                $('#cat_level_2').html("<option value=''>请选择二级分类</option>");
                $('#cat_level_2').append(data);
            },
        });
    });

    //获得三级分类
    $('#cat_level_2').change(function () {
        //清除商品分类列表
        $('#cat_id').empty();
        //获取上级分类的ID
        var value = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{url('admin/Goods/categoryList/getCategory')}}",
            data: {'value': value, '_token': '{{csrf_token()}}'},
            dataType: 'json',
            success: function (data) {
                $('#cat_id').append(data);
            },
        });
    });
});

    function changeStatus(obj)
    {
        if($(obj).attr('data') == 1){
            $.ajax({
                type: 'post',
                url: $(obj).attr('data-url'),
                data: {
                    'goods_id': $(obj).attr('data-id'),
                    'is_on_sale': $(obj).attr('data-status'),
                    '_token': '{{csrf_token()}}',
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        layer.msg(data.msg, {icon: 6});
                        location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 5});
                    }
                }
            })
        }else if($(obj).attr('data') == 2){
            $.ajax({
                type: 'post',
                url: $(obj).attr('data-url'),
                data: {
                    'goods_id': $(obj).attr('data-id'),
                    'is_recommend': $(obj).attr('data-status'),
                    '_token': '{{csrf_token()}}',
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        layer.msg(data.msg, {icon: 6});
                        location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 5});
                    }
                }
            })
        }else if($(obj).attr('data') == 3){
            $.ajax({
                type: 'post',
                url: $(obj).attr('data-url'),
                data: {
                    'goods_id': $(obj).attr('data-id'),
                    'is_new': $(obj).attr('data-status'),
                    '_token': '{{csrf_token()}}',
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        layer.msg(data.msg, {icon: 6});
                        location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 5});
                    }
                }
            })
        }else if($(obj).attr('data') == 4){
            $.ajax({
                type: 'post',
                url: $(obj).attr('data-url'),
                data: {
                    'goods_id': $(obj).attr('data-id'),
                    'is_hot': $(obj).attr('data-status'),
                    '_token': '{{csrf_token()}}',
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        layer.msg(data.msg, {icon: 6});
                        location.reload();
                    } else {
                        layer.msg(data.msg, {icon: 5});
                    }
                }
            })
        }
    }
</script>
    <!-- /.content -->
</div></body></html>
