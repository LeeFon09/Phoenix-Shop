@include('Admin.Public.min-header')
<div class="wrapper">
	@include('Admin.Public.breadcrumb')
	<section class="content">
       <div class="row">
       		<div class="col-xs-12">
	       		<div class="box">
	             <div class="box-header">
              		<nav class="navbar navbar-default">				
	               		<div class="pull-right navbar-form">
	               			<label><a class="btn btn-block btn-primary" href="{{url('admin/Ad/position')}}">新增广告位</a></label>
	               		</div>
	               	</nav>	            
	             </div>
	             <div class="box-body">
		           <div class="row">
		            	<div class="col-sm-12">
			              <table id="list-table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			                 <thead>
			                   <tr role="row">
			                   	   <th>广告ID</th>
				                   <th>广告位名称</th>
				                   <th>广告位宽度</th>
				                   <th>广告位高度</th>
				                   <th>广告位描述</th>
				                   <th>状态</th>
				                   <th>操作</th>
			                   </tr>
			                 </thead>
							<tbody align="center">
								@foreach($list as $k=>$vo)
							  	<tr role="row">
									<td>{{$vo->position_id}}</td>
								 	<td>{{$vo->position_name}}</td>
									<td>{{$vo->ad_width}}</td>
									<td>{{$vo->ad_height}}</td>
									<td>{{$vo->position_desc}}</td>
									<td>
										<img width="20" height="20" onclick="changeStatus(this)" data-id="{{$vo->position_id}}" data-status="{{$vo->is_open}}" data-url="{{url('admin/Article/changeStatus')}}" src="{{$vo->is_open == 1 ? URL::asset('images/yes.png') : URL::asset('images/cancel.png')}}" />
									</td>
									<td>
										<a class="btn btn-info" href="{{url('admin/Ad/positionShow/'.$vo->position_id)}}">查看广告</a>
										<a class="btn btn-primary" href="{{url('admin/Ad/positionEdit/'.$vo->position_id)}}"><i class="fa fa-pencil"></i></a>
									</td>
			                   	</tr>
								@endforeach
			                   </tbody>
			               </table>
		               </div>
		          </div>
		         	
	              <div class="row">
	              	    <div class="col-sm-6 text-left"></div>
	                    <div class="col-sm-6 text-right">
							@if(!empty($pageObj))
								{!! $pageObj->show() !!}
							@endif
						</div>
	              </div>
	          </div>
	        </div>
       	</div>
       </div>
   </section>
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
                'position_id': $(obj).attr('data-id'),
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