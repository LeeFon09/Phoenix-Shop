{{--<include file="Public/min-header"/>--}}
@include('Admin.Public.min-header')
<div class="wrapper">
  {{--<include file="Public/breadcrumb"/>--}}
	@include('Admin.Public.breadcrumb')
	<section class="content">
       <div class="row">
       		<div class="col-xs-12">
	       		<div class="box">
	           	<div class="box-header">
	               <nav class="navbar navbar-default">
				        <div class="collapse navbar-collapse">
				            <div class="navbar-form row">
				            @if(in_array('user',session('url')) || session('admin')->admin_id == '1')
					            <a href="{{url('admin/User/level')}}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>新增等级</a>
					        @endif
				            </div>
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
			                   <th class="sorting" tabindex="0">等级</th>
			                   <th class="sorting" tabindex="0">等级名称</th>
			                   <th class="sorting" tabindex="0">消费额度</th>
			                   <th class="sorting" tabindex="0">折扣率</th>
			                   <th class="sorting" tabindex="0">等级描述</th>
			                   @if(in_array('user',session('url')) || session('admin')->admin_id == '1')
			                   <th class="sorting" tabindex="0">操作</th>
			                   @endif
		                   </tr>
		                 </thead>
						<tbody>
						  {{--<foreach name="list" item="vo" key="k" >--}}
						  @foreach($levelList as $vo)
						  	<tr role="row" align="center">
		                     <td>{{$vo->level_id}}</td>
		                     <td>{{$vo->level_name}}</td>
		                     <td>{{$vo->amount}}</td>
		                     <td>{{$vo->discount}}%</td>
		                     <td>{{$vo->describe}}</td>
		                     @if(in_array('user',session('url')) || session('admin')->admin_id == '1')
		                     <td>
		                      <a class="btn btn-primary" href="{{url('admin/User/levelEdit/'.$vo->level_id)}}"><i class="fa fa-pencil"></i></a>
		                      <a class="btn btn-danger" href="javascript:void(0)" data-url="{{url('admin/User/levelDel')}}" data-id="{{$vo->level_id}}" onclick="delfun(this)"><i class="fa fa-trash-o"></i></a>
							</td>
							@endif
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
<script src="{{URL::asset('js/layer/layer.js')}}"></script>
<script>
function delfun(obj){
	if(confirm('确认删除')){
		$.ajax({
			type : 'post',
			url : $(obj).attr('data-url'),
			data : {level_id:$(obj).attr('data-id'),'_token':'{{csrf_token()}}'},
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
</script>  
</body>
</html>
