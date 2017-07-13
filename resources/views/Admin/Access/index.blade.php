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
				        @if(session('info'))
							<div class="alert alert-success">
			                  {{ session('info') }}
			                </div>
						@endif
				          <form class="navbar-form form-inline" action="{{ url('admin/Admin/access') }}" >
				            <div class="form-group">
				              	<input type="text" name="keyword" class="form-control" placeholder="权限名关键字">
				            </div>
				            <input type="submit" class="btn btn-default" value="提交">
				            <div class="form-group pull-right">
				            	@if(in_array('access',session('url')) || session('admin')->admin_id == '1')
					            <a href="{{ url('admin/Admin/access/create') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>添加权限</a>
					            @endif
				            </div>		          
				          </form>		
				      	</div>
	    			</nav>  
	             </div>	             
	             <div class="box-body">	               
	           		<div class="row">
	            	<div class="col-sm-12">
		              <table id="list-table" class="table table-bordered table-striped dataTable">
		                 <thead>
		                   <tr role="row">
			                   <th>ID</th>
			                   <th>权限名</th>
			                   <th>描述</th>
			                   @if(in_array('access',session('url')) || session('admin')->admin_id == '1')
			                   <th>操作</th>
			                   @endif
		                   </tr>
		                 </thead>
						<tbody>
						  @foreach($access as $v)
					       <tr>
					        <td class="text-center">{{ $v->id }}</td>
					        <td class="text-center">{{ $v->name }}</td>
					        <td class="text-center">{{ $v->desc }}</td>
					        @if(in_array('access',session('url')) || session('admin')->admin_id == '1')
		                     <td class="text-center">
		                      <a class="btn btn-primary" href="{{ url('admin/Admin/access/'.$v->id.'/edit') }}"><i class="fa fa-pencil"></i></a>
		                      
		                      <a class="btn btn-danger" href="{{ url('admin/Admin/access/del/'.$v->id) }}"><i class="fa fa-trash-o"></i></a>
							  @endif
							</td>
		                   </tr>
		                  @endforeach
		                   </tbody>
		                 <tfoot>		                 
		                 </tfoot>
		               </table>	 
	               </div>
	          </div>
              <div class="row" align="right">
              	    {!! $pageObj->show() !!}
              </div>
	         </div>
	        </div>
       	</div>
       </div>
   </section>
</div>
</body>
</html>
