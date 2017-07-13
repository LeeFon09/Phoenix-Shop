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
				          <form class="navbar-form form-inline" action="{{url('admin/Article/linkList')}}" method="post">
							  {{csrf_field()}}
				            <div class="form-group">
				              	<input type="text" name="keyword" class="form-control" placeholder="搜索">
				            </div>
				            <button type="submit" class="btn btn-default">提交</button>
				            <div class="form-group pull-right">
					            <a href="{{url('admin/Article/link')}}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>新增链接</a>
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
			                   <th class="sorting" tabindex="0">排序</th>
			                   <th class="sorting" tabindex="0">链接名称</th>
			                   <th class="sorting" tabindex="0">链接地址</th>
			                   <th class="sorting" tabindex="0">新窗口打开</th>
			                   <th class="sorting" tabindex="0">操作</th>
		                   </tr>
		                 </thead>
						<tbody>
						  {{--<foreach name="list" item="vo" key="k" >--}}
						  @foreach($list as $k=>$vo)
						  	<tr role="row" align="center">
		                     <td>{{$vo->orderby}}</td>
		                     <td>{{$vo->link_name}}</td>
		                     <td>{{$vo->link_url}}</td>
		                     <td>
								 <img width="20" height="20" onclick="changeStatus(this)" data-id="{{$vo->link_id}}" data-status="{{$vo->target}}" data-url="{{url('admin/Article/changeStatus')}}" src="{{$vo->target == 1 ? URL::asset('images/yes.png') : URL::asset('images/cancel.png')}}"/>
							 </td>
		                     <td>
		                      <a class="btn btn-primary" href="{{url('admin/Article/linkEdit/'.$vo->link_id)}}"><i class="fa fa-pencil"></i></a>
		                      <a class="btn btn-danger" href="javascript:void(0)" data-url="{{url('admin/Article/linkHandle')}}" data-id="{{$vo->link_id}}" onclick="delfun(this)"><i class="fa fa-trash-o"></i></a>
							</td>
		                   </tr>
		                  {{--</foreach>--}}
						  @endforeach
		                   </tbody>
		                 <tfoot>
		                 
		                 </tfoot>
		               </table>
	               </div>
	          </div>
              <div class="row">
              	    <div class="col-sm-6 text-left"></div>
                    <div class="col-sm-6 text-right">{!! $pageObj->show() !!}</div>
              </div>
	          </div><!-- /.box-body -->
	        </div><!-- /.box -->
       	</div>
       </div>
   </section>
</div>
<script>
	function delfun(obj){
		if(confirm('确认删除')){
			$.ajax({
				type : 'delete',
				url : $(obj).attr('data-url'),
				data : {'link_id':$(obj).attr('data-id'), '_token':'{{csrf_token()}}'},
				dataType : 'json',
				success : function(data){
					if (data.status) {
						layer.msg(data.msg, {icon: 6});
						location.href = location.href;
					} else {
						layer.msg(data.msg, {icon: 5});
					}
				}
			})
		}
		return false;
	}

	function changeStatus(obj)
	{
        $.ajax({
            type: 'post',
            url: $(obj).attr('data-url'),
            data: {
                'link_id': $(obj).attr('data-id'),
                'target': $(obj).attr('data-status'),
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