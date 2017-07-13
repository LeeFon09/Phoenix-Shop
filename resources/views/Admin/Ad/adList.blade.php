{{--<include file="Public/min-header" />--}}
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
				          <form class="navbar-form form-inline" action="{{url('admin/Ad/adList')}}" method="post">
							  {{csrf_field()}}
				            <div class="form-group">
				              	<input type="text" name="keyword" class="form-control" placeholder="请输入广告名称">
				            </div>
				            <div class="form-group">                       
				            	 <select name="pid" class="form-control">
									 <option value="0">==查看所有==</option>
									 @foreach($ad_position_list as $k=>$item)
										 <option value="{{$item->position_id}}">{{$item->position_name}}</option>
									 @endforeach
                                 </select>
				            </div>
				            <button type="submit" class="btn btn-primary">查询</button>
				            <div class="form-group pull-right">
								@if(in_array('ad', session('url')) || session('admin')->admin_id == '1')
					            	<a href="{{url('admin/Ad/ad')}}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> 新增广告</a>
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
							   <th>广告id</th>
							   <th>广告位置</th>
			                   <th>广告名称</th>	
			                   <th>广告图片</th>	                   
			                   <th>广告链接</th>
			                   <th>是否显示</th>
		                  	   <th>排序</th>
							   @if(in_array('ad', session('url')) || session('admin')->admin_id == '1')
		                  	   <th>操作</th>
							   @endif
		                   </tr>
		                 </thead>
						<tbody align="center">
							  @foreach($list as $k=>$vo)
                             <tr role="row">    
								 <td>{{$vo->ad_id}}</td>
								 <td>{{$vo->position_name}}</td>
								 <td>{{$vo->ad_name}}</td>
								 <td><img alt="" src="{{$vo->ad_code}}" width="80px" height="50px"></td>
								 <td>{{$vo->ad_link}}</td>
								 <td>
									 <img width="20" height="20" onclick="changeStatus(this)" data-id="{{$vo->ad_id}}" data-status="{{$vo->enabled}}" data-url="{{url('admin/Comment/changeStatus')}}" src="{{$vo->enabled == 1 ? URL::asset('images/yes.png') : URL::asset('images/cancel.png')}}" />
								 </td>
								 <td>
									 <input type="text" onchange="changeOrder(this, {{$vo->ad_id}})" size="4" value="{{$vo->orderby}}" class="input-sm" />
								 </td>
								 @if(in_array('ad', session('url')) || session('admin')->admin_id == '1')
								 <td>
									  <a class="btn btn-primary" href="{{url('admin/Ad/edit/'.$vo->ad_id)}}"><i class="fa fa-pencil"></i></a>
									  <a class="btn btn-danger" onclick="delfunc(this)" data-url="{{url('admin/Ad/adHandle')}}" data-id="{{$vo->ad_id}}"><i class="fa fa-trash-o"></i></a>
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
	          </div>
	        </div>
       	</div>
       </div>
   </section>
	<script src="{{URL::asset('js/layer/layer.js')}}"></script>
<script>
    function delfunc(obj){
        if(confirm('确认删除')){
            $.ajax({
                type : 'delete',
                url : $(obj).attr('data-url'),
                data : {ad_id:$(obj).attr('data-id'),'_token':'{{csrf_token()}}'},
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

    function changeOrder(obj, ad_id) {
        var ad_order = $(obj).val();
        $.post("{{url('admin/Ad/changeOrder')}}", {
            '_token': '{{csrf_token()}}',
            'ad_id': ad_id,
            'ad_order': ad_order
        }, function (data) {
            if (data.status) {
                layer.msg(data.msg, {icon: 6});
            } else {
                layer.msg(data.msg, {icon: 5});
            }
        });
    }

    function changeStatus(obj)
    {
		$.ajax({
			type: 'post',
			url: $(obj).attr('data-url'),
			data: {
				'ad_id': $(obj).attr('data-id'),
				'enabled': $(obj).attr('data-status'),
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
</div>
</body>
</html>   
