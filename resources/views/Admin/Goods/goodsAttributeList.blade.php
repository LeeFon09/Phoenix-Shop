@include('Admin.Public/min-header')
<div class="wrapper">
  @include('Admin/Public/breadcrumb')
  <!-- Main content -->
  <section class="content">
  @if (session('info'))
      <div class="alert alert-success">
          {{ session('info') }}
      </div>
  @endif
    <div class="container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> 商品属性</h3>
        </div>
        <div class="panel-body">
          <div class="navbar navbar-default">
              <form id="search-form2" class="navbar-form form-inline" action="{{ url('admin/Goods/goodsAttributeList') }}">
                <div class="form-group">
                 <select name="type_id" id="type_id" class="form-control">
                    <option value="">所有分类</option>
                        @foreach($type as $t)
                           <option value="{{$t->id}}">{{$t->name}}</option>
                        @endforeach
                  </select>
                </div>                  
               <div class="form-group">
                  <input type="submit" value="筛选" id="button-filter" class="btn btn-primary pull-right">         
                </div>
                @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                <button type="button" onclick="location.href='{{ url('admin/Goods/goodsAttributeList/create') }}'" class="btn btn-primary pull-right">
                 <i class="fa fa-plus"></i> 添加属性
                </button>
                @endif
              </form>
          </div>
          <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>               
                <th class="sorting text-center">ID</th>
                <th class="sorting text-center">属性名称</th>
                <th class="sorting text-center">商品类型</th>
                <th class="sorting text-center">属性值的输入方式</th>
                <th class="sorting text-center" width="200">可选值列表</th>
                <th class="sorting text-center">筛选</th>
                 @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                <th class="sorting text-center">操作</th> 
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($attr as $v)
            <tr>
                <td class="text-center">{{ $v->attr_id }}</td>
                <td class="text-center">{{ $v->attr_name }}</td>
                <td class="text-center">{{ $v->type }}</td>
                <td class="text-center">
                  @if($v->attr_input_type == 0)
                      手工录入
                    @elseif($v->attr_input_type == 1)
                      从列表中选择
                    @else
                      多行文本框
                  @endif
                </td>
                <td class="text-center">{{ $v->attr_values }}</td>
                <td class="text-center">
                    <img width="20" height="20" onclick="changeStatus(this)" data-id="{{$v->attr_id}}" data-status="{{$v->attr_index}}" data-url="{{url('admin/Goods/goodsAttributeList/changeStatus')}}" src="{{$v->attr_index == 0 ? url('images/cancel.png') : url('images/yes.png') }}">
                </td>   
                 @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')                 
                <td class="text-center">                       
                    <a href="{{ url('admin/Goods/goodsAttributeList/'.$v->attr_id.'/edit') }}" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                    <a href="{{ url('admin/Goods/goodsAttributeList/del/'.$v->attr_id) }}" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a></td>
                  @endif
                </tr>  
              @endforeach          
            </tbody>
        </table>
    </div>
    <div align="right">{!! $pageObj->show() !!}</div>
        </div>
      </div>
    </div>
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
</body>
</html>
<script>
    function changeStatus(obj)
    {
        $.ajax({
            type: 'post',
            url: $(obj).attr('data-url'),
            data: {
                'attr_id': $(obj).attr('data-id'),
                'attr_index': $(obj).attr('data-status'),
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
</script>