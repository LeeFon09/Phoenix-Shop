@include('Admin.Public.min-header')
<div class="wrapper">
    @include('Admin.Public.breadcrumb')
		<section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">修改商品分类</h3>
			                <div class="pull-right">
			                	<a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
			                	<a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/1006/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
			                </div>
                        </div>
 
                        <!-- /.box-header -->
                        <form action="{{ url('admin/Goods/categoryList/'.$goods_category_info[0]->id) }}" method="post" class="form-horizontal" id="category_form"  enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="box-body">
                                <div class="form-group">
                                     <label class="col-sm-2 control-label">分类名称</label>
                                     <div class="col-sm-6">
                                        <input type="text" placeholder="名称" class="form-control large" name="name" value="{{$goods_category_info[0]->name}}">
                                        <span class="help-inline" style="color:#F00; display:none;" id="err_name"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">手机端分类名称</label>
                                    <div class="col-sm-6">
                                        <input type="text" placeholder="手机分类名称" class="form-control large" name="mobile_name" value="{{$goods_category_info[0]->mobile_name}}">
                                        <span class="help-inline" style="color:#F00; display:none;" id="err_mobile_name"></span>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label0 class="control-label col-sm-2">上级分类</label0>

                                    <div class="col-sm-3">
                                        <select name="parent_id_1" id="parent_id_1"  class="small form-control">
	                                        <option value="0">顶级分类</option>
                                                @foreach($cat_list as $v)
                                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                @endforeach                                            
										</select>
                                    </div>                                    
                                    <div class="col-sm-3">
                                      <select name="parent_id_2" id="parent_id_2"  class="small form-control">
                                        <option value="0">请选择商品分类</option>
                                      </select>  
                                    </div>                                      
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">导航显示</label>
									
                                    <div class="col-sm-10">
                                        <label> 
                                        @if($goods_category_info[0]->is_show == 1)
                                             <input checked="checked" type="radio" name="is_show" value="1"> 是
                                             <input type="radio" name="is_show" value="0"> 否
                                        @else
                                            <input type="radio" name="is_show" value="1"> 是
                                                <input checked="checked" type="radio" name="is_show" value="0"> 否
                                        @endif
                                                                                                                  
                                        </label>
                                    </div>
                                </div>
				<div class="form-group">
                                    <label class="control-label col-sm-2">分类分组:</label>
									
                                    <div class="col-sm-1">
                                      <select name="cat_group" id="cat_group" class="form-control">
                                        <option value="0">0</option>
                                        <option value="1">1</option>           
                                        <option value="2">2</option>          
                                        <option value="3">3</option>             
                                        <option value="4">4</option>               
                                        <option value="5">5</option>             
                                        <option value="6">6</option>             
                                        <option value="7">7</option>             
                                        <option value="8">8</option>            
                                        <option value="9">9</option>             
                                        <option value="10">10</option>            
                                        <option value="11">11</option>           
                                        <option value="12">12</option>                         
                                        <option value="13">13</option>                            
                                        <option value="14">14</option>                               
                                        <option value="15">15</option>                            
                                        <option value="16">16</option>                                
                                        <option value="17">17</option>                              
                                        <option value="18">18</option>                               
                                        <option value="19">19</option>
                                         <option value="29">20</option>                               
                                      </select>                                        
                                    </div>                                    
                                </div>   
                             
                				<div class="form-group">
	                               <label class="control-label col-sm-2">分类展示图片</label>

                                    <div class="col-sm-10">
                                        <input type="file" value="上传图片" name="picname"/>
                                        <input type="text" value="{{$goods_category_info[0]->image}}" name="image" id="image" class="form-control large" readonly="readonly"  style="width:500px;display:initial;"/>
                                    </div>
                                </div>                                
                               <div class="form-group">
                                    <label class="control-label col-sm-2">显示排序</label>
                                    <div class="col-sm-1">
                                        <input type="text" placeholder="50" class="form-control large" name="sort_order" value="{{$goods_category_info[0]->sort_order}}"/>
                                        <span class="help-inline" style="color:#F00; display:none;" id="err_sort_order"></span>
                                    </div>
                                </div>
                        </div>
                        <div class="box-footer">                        	
                            <input type="hidden" name="id" value="{{$goods_category_info[0]->id}}">                           
                        	<input type="reset" class="btn btn-primary pull-left">  </input>                       	                 
                            <input type="submit" value="提交" class="btn btn-primary pull-right">
                        </div> 
                        </form>
                    </div>
                </div>
            </div>
        </section>
</div>
<script>  
    
/** 以下是编辑时默认选中某个商品分类*/
$(document).ready(function(){

   
	// <if condition="$level_cat['2'] gt 0">	
	// 	 // 如果当前是二级分类就让一级父id默认选中
	// 	 $("#parent_id_1").val('{$level_cat[1]}'); 
	// 	 get_category('{$level_cat[1]}','parent_id_2','0');		 
	// </if>	 
   
	// <if condition="$level_cat['3'] gt 0">
	// 	 // 如果当前是三级分类就一级和二级父id默认 都选中
	// 	 $("#parent_id_1").val('{$level_cat[1]}');		 	
	// 	 get_category('{$level_cat[1]}','parent_id_2','{$level_cat[2]}');	
	// </if>	
});

    $('#parent_id_1').change( function () {
        //清除商品分类列表
       $('#parent_id_2 option[value!="0"]').remove();
           //获取上级分类的ID
           var value = $(this).val();
           if(value!='0'){
                 $.ajax({

                    type: 'POST',

                    url: "{{url('admin/Goods/categoryList/getCategory')}}",

                    data: { 'value' : value, '_token':'{{csrf_token()}}'},

                    dataType: 'json',

                    success: function(data){

                        $('#parent_id_2').append(data);
                        // console.log(data);

                    },

                });
           }
          
    });

</script>
   
</body>
</html>
