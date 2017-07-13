@include('Admin.Public.min-header')
<div class="wrapper">
  @include('Admin.Public.breadcrumb')
	<section class="content">
       <div class="row">
       		<div class="col-xs-12">
	       		<div class="box">
	           	<div class="box-header">
	                <nav class="navbar navbar-default">	     
				        <div class="collapse navbar-collapse">
				          <form class="navbar-form form-inline" action="{{ url('admin/Article/articleList/search') }}" method="post">
				          {{ csrf_field() }}
				            <div class="form-group">
				              	<input type="text" name="keyword" class="form-control" placeholder="关键字">
				            </div>
				           	<div class="form-group">
				              	<select name="cat_id" class="form-control" style="width:200px;">
				              		<option value="">选择文章类别</option>
				              		@foreach($type as $t)
				              		<option value="{{$t->cat_id}}">{{$t->cat_name}}</option>
				              		@endforeach
				              	</select>
				            </div>
				            <input type="submit" class="btn btn-default" value="提交">
				            <div class="form-group pull-right">
				            	@if(in_array('content',session('url')) || session('admin')->admin_id == '1')
					            <a href="{{url('admin/Article/articleList/create')}}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>添加文章</a>
					            @endif
				            </div>		          
				          </form>		
				      	</div>
	    			</nav>               
	             </div>	    
	             <!-- /.box-header -->
	             <div class="box-body">	             
	           		<div class="row">
	            	<div class="col-sm-12">
		              <table id="list-table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
		                 <thead>
		                   <tr role="row">
			                   <th class="sorting_asc text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 294px;">文章标题</th>
			                   <th class="sorting text-center" tabindex="0" aria-controls="example1"  aria-label="Browser: activate to sort column ascending">文章类别</th>
			                   <th class="sorting text-center" tabindex="0" aria-controls="example1"  aria-label="Platform(s): activate to sort column ascending">描述</th>
			                   <th class="sorting text-center" tabindex="0" aria-controls="example1"  aria-label="Platform(s): activate to sort column ascending">显示</th>
			                   <th class="sorting text-center" tabindex="0" aria-controls="example1"  aria-label="Engine version: activate to sort column ascending">发布时间</th>
			                   @if(in_array('content',session('url')) || session('admin')->admin_id == '1')
			                   <th class="sorting text-center" tabindex="0" aria-controls="example1"  aria-label="CSS grade: activate to sort column ascending">操作</th>
			                   @endif
		                   </tr>
		                 </thead>
						<tbody>
						  @foreach($article as $v)
						  	<tr role="row" align="center">
		                     <td>{{ $v->title }}</td>
		                     <td>{{ $v->type }}</td>
		                     <td>{{ $v->keywords }}</td>
		                     <td>
								 <img width="20" height="20" onclick="changeStatus(this)" data-id="{{$v->article_id}}" data-status="{{$v->is_open}}" data-url="{{url('admin/Article/changeStatus')}}" src="{{$v->is_open == 1 ? url('images/yes.png') : url('images/cancel.png') }}"/>
							 </td>
		                     <td>{{ date('Y-m-d H:i:s',$v->publish_time) }}</td>
		                     @if(in_array('content',session('url')) || session('admin')->admin_id == '1')
		                     <td>
		                      	 <a target="_blank" href="{{url('home/article/detail/'.$v->article_id)}}" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-eye"></i></a>
		                      	 <a class="btn btn-primary" href="{{url('admin/Article/articleList/'.$v->article_id.'/edit')}}"><i class="fa fa-pencil"></i></a>
								 <a class="btn btn-danger" onclick="delfunc(this)" data-url="{{url('admin/Article/articleList/'.$v->article_id)}}" data-id="{{$v->article_id}}"><i class="fa fa-trash-o"></i></a>
				     		</td>
				     		@endif
		                    </tr>
		                  @endforeach
		                   </tbody>
		               </table>
	               </div>
	          </div>
					 <div class="row">
						 <div class="col-sm-6 text-left">
							 {{--<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>--}}
						 </div>
						 <div class="col-sm-6 text-right">
							 @if(!empty($pageObj))
								 {!! $pageObj->show() !!}
							 @endif
						 </div>
					 </div>
	          </div><!-- /.box-body -->
	        </div><!-- /.box -->
       	</div>
       </div>
   </section>
</div>
<script>
    function delfunc(obj){
        if(confirm('确认删除')){
            $.ajax({
                type : 'delete',
                url : $(obj).attr('data-url'),
                data : {article_id:$(obj).attr('data-id'),'_token':'{{csrf_token()}}'},
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

    function changeStatus(obj)
    {
        $.ajax({
            type: 'post',
            url: $(obj).attr('data-url'),
            data: {
                'article_id': $(obj).attr('data-id'),
                'is_open': $(obj).attr('data-status'),
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
</body>
</html>
