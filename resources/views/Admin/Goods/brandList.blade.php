@include('Admin.Public/min-header')
<div class="wrapper">
  @include('Admin/Public/breadcrumb')
  <section class="content">
    <div class="container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading">
         @if (session('info'))
              <div class="alert alert-success">
                  {{ session('info') }}
              </div>
          @endif
          <h3 class="panel-title"><i class="fa fa-list"></i> 品牌列表</h3>
        </div>
        <div class="panel-body">    
		<div class="navbar navbar-default">                    
                <form id="search-form2" class="navbar-form form-inline"   action="{{ url('admin/Goods/brandList') }}">
               
                <div class="form-group">
                  <label for="input-order-id" class="control-label">名称:</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="input-order-id" placeholder="搜索词" name="keyword">                    
                  </div>
                </div>
                <div class="form-group">    
                    <input class="btn btn-primary" id="button-filter search-order" type="submit" value="筛选">   
                </div> 
                @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')               
                <button type="button" class="btn btn-primary pull-right"  onclick="location.href='{{url('admin/Goods/brandList/create')}}'">
                 <i class="fa fa-plus"></i> 添加品牌
                </button>  
                @endif              
                </form>    
          </div>
                        
          <div id="ajax_return"> 
                 
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="sorting text-center">ID</th>
                                <th class="sorting text-center">品牌名称</th>
                                <th class="sorting text-center">Logo</th>
                                <th class="sorting text-center">品牌分类</th>
                                <th valign="middle">是否推荐</th>
                                @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                                <th class="sorting text-center">操作</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            <volist name="brandList" id="list">
                            @foreach($brand as $v)
                                <tr>
                                    <td class="text-center">{{ $v->id }}</td>
                                    <td class="text-center">{{ $v->name }}</td>
                                    <td class="text-center">
                                        <img onmouseover="$(this).attr('width','80').attr('height','45');" onmouseout="$(this).attr('width','40').attr('height','30');" width="40" height="30" src="{{ url($v->logo) }}"/>                                  
                                    </td>
                                    <td class="text-center">{{ $v->cat_name }}</td>                                   
                                    <td class="text-center">
                                        <img width="20" height="20" onclick="changeStatus(this)" data-id="{{$v->id}}" data-status="{{$v->is_hot}}" data-url="{{url('admin/Goods/brandList/changeStatus')}}" src="{{$v->is_hot == 0 ? url('images/cancel.png') : url('images/yes.png')}}"/>
                                    </td>
                                    @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                                    <td class="text-center">
                                        <a href="{{ url('admin/Goods/brandList/'.$v->id.'/edit') }}" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <a href="{{ url('admin/Goods/brandList/del/'.$v->id) }}"  id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                            </volist>
                            </tbody>
                        </table>
                    </div>
                
                <div class="row" align="right">
                    {!! $pageObj->show() !!}
   
                </div>
          
          </div>
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
                'id': $(obj).attr('data-id'),
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
</script>