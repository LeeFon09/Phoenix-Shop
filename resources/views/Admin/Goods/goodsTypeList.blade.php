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
          <h3 class="panel-title"><i class="fa fa-list"></i> 商品类型列表</h3>
        </div>
        <div class="panel-body">    
		<div class="navbar navbar-default">
            <div class="row navbar-form">
               @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                <button type="submit" onclick="location.href='{{ url('admin/Goods/goodsTypeList/create') }}'"  class="btn btn-primary pull-right"><i class="fa fa-plus"></i>新增商品类型</button>
              @endif
            </div>
          </div>
                        
          <div id="ajax_return"> 
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="sorting text-center">ID</th>                                
                                <th class="sorting text-center">类型名</th>
                                 @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                                <th class="sorting text-center">操作</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($goodsType as $v)
                            <volist name="goodsTypeList" id="list">
                                <tr>
                                    <td class="text-center">{{$v->id}}</td>
                                    <td class="text-center">{{$v->name}}</td>
                                     @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
                                    <td class="text-center">
                    										<a href="{{ url('admin/Goods/goodsAttributeList/search/'.$v->id) }}" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="属性列表"><i class="fa fa-eye"></i></a>                                    
                                        <a href="{{ url('admin/Goods/goodsTypeList/'.$v->id.'/edit') }}" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                                        <a href="{{ url('admin/Goods/goodsTypeList/del/'.$v->id) }}" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
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
<!-- /.content-wrapper --> 
</body>
</html>
