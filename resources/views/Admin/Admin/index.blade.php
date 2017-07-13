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
				          <form class="navbar-form form-inline" action="{{ url('admin/Admin/index') }}">
				            <div class="form-group">
				              	<input type="text" name="keywords" class="form-control" placeholder="用户名关键字">
				            </div>
				            <button type="submit" class="btn btn-default">提交</button>
				            <div class="form-group pull-right">
				            @if(in_array('admin',session('url')) || session('admin')->admin_id == '1')
					            <a href="{{ url('admin/Admin/index/create') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>添加管理员</a>
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
			                   <th>用户名</th>
			                   <th>所属角色</th>
			                   <th>Email地址</th>
			                   <th>加入时间</th>
			                   @if(in_array('admin',session('url')) || session('admin')->admin_id == '1')
			                   <th>操作</th>
			                   @endif
		                   </tr>
		                 </thead>
						<tbody>
						  @foreach($admin as $v)
						  	<tr role="row" align="center">
		                     <td>{{$v->admin_id}}</td>
		                     <td>{{$v->user_name}}</td>
		                     <td>{{$v->role}}</td>
		                     <td>{{$v->email}}</td>
		                     <td>{{date('Y-m-d',$v->add_time)}}</td>
		                     @if(in_array('admin',session('url')) || session('admin')->admin_id == '1')
		                     <td>
		                      <a class="btn btn-primary" href="{{ url('admin/Admin/index/'.$v->admin_id.'/edit') }}"><i class="fa fa-pencil"></i></a>
		                      
		                      <a class="btn btn-danger" href="{{ url('admin/Admin/del/'.$v->admin_id) }}"><i class="fa fa-trash-o"></i></a>
							  
							</td>
							@endif
		                   </tr>
		                  @endforeach
		                   </tbody>
		                 <tfoot>		                 
		                 </tfoot>
		               </table>	 
	               </div>
	          </div>
              <div align="right">
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
