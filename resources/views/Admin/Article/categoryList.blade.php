@include('Admin.Public/min-header')
<div class="wrapper">
  @include('Admin/Public/breadcrumb')
	<section class="content">
       <div class="row">
       		<div class="col-xs-12">
	       		<div class="box">
	             <div class="box-header">
	             	<nav class="navbar navbar-default">	     
				        <div class="collapse navbar-collapse">
				        <form class="navbar-form form-inline" action="" method="post" id="catform">
				            <div class="form-group pull-right">
				            @if(in_array('content',session('url')) || session('admin')->admin_id == '1')
					            <a href="{{url('admin/Article/category')}}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>新增分类</a>
					        @endif
				            </div>		          
				          </form>
				      	</div>
	    			</nav> 	               
	             </div><!-- /.box-header -->
	           <div class="box-body">
	           <div class="row">
	            <div class="col-sm-12">
	              <table id="list-table" class="table table-bordered table-striped">
	                 <thead>
	                   <tr role="row">
		                   <th  style="width: 350px;" class="text-center">分类名称</th>
		                   <th class="text-center">描述</th>
		                   <th class="text-center">是否显示</th>
		                   @if(in_array('content',session('url')) || session('admin')->admin_id == '1')
		                   <th class="text-center">操作</th>
		                   @endif
	                   </tr>
	                 </thead>
					<tbody>
					  @foreach($articleCat2 as $v)				  
						<tr role="row">
	                      <td class="sorting_1" align="left" style="padding-left:{{  ($v->level - 1) * 4 }}em">    		    
					      <span>{{$v->cat_name}}</span>
					     </td>					     
					     <td class="text-center">{{$v->cat_desc}}</td>
	                     <td class="text-center">
							 <img width="20" height="20" onclick="changeStatus(this)" data-id="{{$v->cat_id}}" data-status="{{$v->show_in_nav}}" data-url="{{url('admin/Article/changeStatus')}}" src="{{$v->show_in_nav == 1 ? url('images/yes.png') : url('images/cancel.png') }}">
                         </td>
                         @if(in_array('content',session('url')) || session('admin')->admin_id == '1')
	                     <td class="text-center">
	                      <a class="btn btn-primary" href="{{url('admin/Article/categoryEdit/'.$v->cat_id)}}"><i class="fa fa-pencil"></i></a>
							 @if($v->cat_type != 1)
	                      		{{--<a class="btn btn-danger" href="javascript:void(0)" data-url="{:U('Article/categoryHandle')}" data-id="{$vo.cat_id}" onclick="delfun(this)"><i class="fa fa-trash-o"></i></a>--}}
								 <a class="btn btn-danger" onclick="delfunc(this)" data-url="{{url('admin/Article/categoryHandle')}}" data-id="{{$v->cat_id}}"><i class="fa fa-trash-o"></i></a>
							 @else
						  		<a class="btn btn-default disabled" href="javascript:void(0)"><i class="fa fa-trash-o"></i></a>
							 @endif
						</td>
						@endif
	                   </tr>
	                  @endforeach
	                   </tbody>
	               </table></div></div>
		               <div class="row">
			               <div class="col-sm-5">
			               <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">分页</div></div>                                   
		               </div>
	             </div><!-- /.box-body -->
	           </div><!-- /.box -->
       		</div>
       </div>
     </section>
</div>

</body>
</html>
<script>
    function delfunc(obj){
        if(confirm('确认删除')){
            $.ajax({
                type : 'delete',
                url : $(obj).attr('data-url'),
                data : {cat_id:$(obj).attr('data-id'),'_token':'{{csrf_token()}}'},
                dataType : 'json',
                success : function(data){
                    if (data.status) {
                        layer.msg(data.msg, {icon: 5});
                    } else {
                        layer.msg(data.msg, {icon: 6});
                        location.href = location.href;
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
                'cat_id': $(obj).attr('data-id'),
                'show_in_nav': $(obj).attr('data-status'),
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
