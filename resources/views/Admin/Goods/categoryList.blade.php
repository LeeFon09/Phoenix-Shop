@include("Admin.Public.min-header")
<div class="wrapper">
 @include("Admin.Public.breadcrumb")
	<section class="content">
       <div class="row">
       		<div class="col-xs-12">
	       		<div class="box">
	             <div class="box-header">
               @if (session('info'))
                  <div class="alert alert-success">
                      {{ session('info') }}
                  </div>
              @endif

	               	<nav class="navbar navbar-default">	     
				        <div class="collapse navbar-collapse">
						   <div class="navbar-form row">
				            	<div class="col-md-1">
									<button class="btn bg-navy" type="button" onclick="tree_open(this);"><i class="fa fa-angle-double-down"></i>展开</button>
					            </div>
					            <div class="col-md-9">
					            	<span class="warning">温馨提示：顶级分类（一级大类）设为推荐时才会在首页楼层中显示</span>
					            </div>
					            <div class="col-md-2">
                      @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
					            <a href="{{ url('admin/Goods/categoryList/create') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>新增分类</a>
                      @endif
				            	</div>
				            </div>
				      	</div>
	    			</nav> 	               
	             </div><!-- /.box-header -->
	           <div class="box-body">
	           <div class="row">
	            <div class="col-sm-12">
	              <table id="list-table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
	                 <thead>
	                   <tr role="row">
	                   	 <th class="text-center">分类ID</th>
		                   <th class="text-center">分类名称</th>
                        <th class="text-center">手机显示名称</th>
                        <th class="text-center">是否推荐</th>
		                   <th class="text-center">是否显示</th>
                        <th class="text-center">分组</th>
		                   <th class="text-center">排序</th>
                       @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
		                   <th class="text-center">操作</th>
                       @endif
	                   </tr>
	                 </thead>
			<tbody>
			@foreach($cat_list as $vo)
            @if($vo->level > 1)
              <tr role="row" align="center" class="{{$vo->level}}" id="{{$vo->level}}_{{$vo->id}}" style="display:none">
            @else
               <tr role="row" align="center" class="{{$vo->level}}" id="{{$vo->level}}_{{$vo->id}}">
            @endif
			  			 <td>{{$vo->id}}</td>
	                     <td align="left" style="padding-left:{{ ($vo->level * 5) }}em">
                       @if(isset($vo->have_son)) 
		                   <span class="glyphicon glyphicon-plus btn-warning" style="padding:2px; font-size:12px;"  id="icon_{{$vo->level}}_{{$vo->id}}" aria-hidden="false" onclick="rowClicked(this)" ></span>&nbsp;	
                       @endif			    
                             <span>{{$vo->name}}</span>
			     		 </td>
                         <td><span>{{$vo->mobile_name}}</span></td>
                         <td>
                              <img onclick="changeStatus(this)" data="1" data-id="{{$vo->id}}" data-status="{{$vo->is_hot}}" data-url="{{url('admin/Goods/categoryList/changeStatus')}}" src="{{$vo->is_hot == 1 ? url('images/yes.png') : url('images/cancel.png')}}" height="20" width="20" />
                         </td>
                         <td>
                              <img onclick="changeStatus(this)" data="2" data-id="{{$vo->id}}" data-status="{{$vo->is_show}}" data-url="{{url('admin/Goods/categoryList/changeStatus')}}" src="{{$vo->is_show == 1 ? url('images/yes.png') : url('images/cancel.png')}}" width="20" height="20" />
                         </td>
	                     <td>                                 
                         	{{$vo->cat_group}}
                        </td>                         
	                     <td>
                         	{{$vo->sort_order}}
                        </td>
                        @if(in_array('goods',session('url')) || session('admin')->admin_id == '1')
	                     <td>
	                      <a class="btn btn-primary" href="{{url('admin/Goods/categoryList/'.$vo->id.'/edit')}}"><i class="fa fa-pencil"></i></a>
	                      <a class="btn btn-danger" href="{{url('admin/Goods/categoryList/del/'.$vo->id)}}"><i class="fa fa-trash-o"></i></a>
        			     		</td>
                      @endif
	                   </tr>
	                 @endforeach
	                   </tbody>
	               </table></div></div>
		               <div class="row">
			               <div class="col-sm-5">
			               <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
                     
                     </div></div>                                   
		               </div>
	             </div><!-- /.box-body -->
	           </div><!-- /.box -->
       		</div>
       </div>
     </section>

</div>
<script type="text/javascript">

// 展开收缩
function  tree_open(obj)
{
	 var tree = $('#list-table tr[id^="2_"], #list-table tr[id^="3_"] ');
	 if(tree.css('display')  == 'table-row')
	 {
		 $(obj).html("<i class='fa fa-angle-double-down'></i>展开");
		tree.css('display','none');
		$("span[id^='icon_']").removeClass('glyphicon-minus');
		$("span[id^='icon_']").addClass('glyphicon-plus');
	 }else
	 {
		 $(obj).html("<i class='fa fa-angle-double-up'></i>收缩");
		tree.css('display','table-row');
		$("span[id^='icon_']").addClass('glyphicon-minus');
		$("span[id^='icon_']").removeClass('glyphicon-plus');
	 }
}
    
// 以下是 bootstrap 自带的  js
function rowClicked(obj)
{
  span = obj;

  obj = obj.parentNode.parentNode;

  var tbl = document.getElementById("list-table");

  var lvl = parseInt(obj.className);

  var fnd = false;
  
  var sub_display = $(span).hasClass('glyphicon-minus') ? 'none' : '' ? 'block' : 'table-row' ;
  //console.log(sub_display);
  if(sub_display == 'none'){
	  $(span).removeClass('glyphicon-minus btn-info');
	  $(span).addClass('glyphicon-plus btn-warning');
  }else{
	  $(span).removeClass('glyphicon-plus btn-info');
	  $(span).addClass('glyphicon-minus btn-warning');
  }

  for (i = 0; i < tbl.rows.length; i++)
  {
      var row = tbl.rows[i];
      
      if (row == obj)
      {
          fnd = true;         
      }
      else
      {
          if (fnd == true)
          {
              var cur = parseInt(row.className);
              var icon = 'icon_' + row.id;
              if (cur > lvl)
              {
                  row.style.display = sub_display;
                  if (sub_display != 'none')
                  {
                      var iconimg = document.getElementById(icon);
                      $(iconimg).removeClass('glyphicon-plus btn-info');
                      $(iconimg).addClass('glyphicon-minus btn-warning');
                  }else{               	    
                      $(iconimg).removeClass('glyphicon-minus btn-info');
                      $(iconimg).addClass('glyphicon-plus btn-warning');
                  }
              }
              else
              {
                  fnd = false;
                  break;
              }
          }
      }
  }

  for (i = 0; i < obj.cells[0].childNodes.length; i++)
  {
      var imgObj = obj.cells[0].childNodes[i];
      if (imgObj.tagName == "IMG")
      {
          if($(imgObj).hasClass('glyphicon-plus btn-info')){
        	  $(imgObj).removeClass('glyphicon-plus btn-info');
        	  $(imgObj).addClass('glyphicon-minus btn-warning');
          }else{
        	  $(imgObj).removeClass('glyphicon-minus btn-warning');
        	  $(imgObj).addClass('glyphicon-plus btn-info');
          }
      }
  }
}

    function changeStatus(obj)
    {
        if($(obj).attr('data') == 1){
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
        }else if($(obj).attr('data') == 2){
            $.ajax({
                type: 'post',
                url: $(obj).attr('data-url'),
                data: {
                    'id': $(obj).attr('data-id'),
                    'is_show': $(obj).attr('data-status'),
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
    }
</script>
</body>
</html>
  
