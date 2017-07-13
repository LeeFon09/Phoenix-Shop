@include("Admin.Public.min-header")
<div class="wrapper">
 @include("Admin.Public.breadcrumb")
    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
           		<a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/1010/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 品牌详情</h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">商品类型</a></li>
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="addEditBrandForm" action="{{ url('admin/Goods/brandList') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}             
                        <!--通用信息-->
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>品牌名称:</td>
                                    <td>
                                        <input type="text" value="{{ old('name') }}" name="name" class="form-control" style="width:200px;"/>                         
                                    </td>
                                </tr>                                
                                <tr>
                                    <td>品牌网址:</td>
                                    <td>
                                        <input type="text" value="{{ old('url') }}" name="url" class="form-control" style="width:250px;"/>
                                        <span id="err_url" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr>                                                                
                                <tr>
                                    <td>所属分类:</td>
                                    <td>
                                        <div class="col-sm-3">
	                                        <select name="parent_cat_id" id="parent_id_1" class="form-control" style="width:250px;margin-left:-15px;">
                                                    <option value="">请选择分类</option> 
	                                           @foreach($type as $v)                                                                                    
	                                                <option value="{{ $v->id }}">{{$v->name}}</option>
	                                            @endforeach                                           
						</select>
	                                    </div>                                    
	                                    <div class="col-sm-3">
	                                      <select name="cat_id" id="parent_id_2"  class="form-control" style="width:250px;">
	                                        <option value="">请选择分类</option>
	                                      </select>  
	                                    </div>     
                                    </td>
                                </tr>                                
                                <tr>
                                    <td>品牌logo:</td>
                                    <td>  
                                    	<div class="col-sm-3">                                                                              
                                        	<input type="file" name="logo">
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>品牌排序:</td>
                                    <td>
                                        <input type="text" value="{{ old('sort') }}" name="sort" class="form-control" style="width:200px;" placeholder="50"/>                                
                                    </td>
                                </tr>                                                                 
                                <tr>
                                    <td>品牌描述:</td>
                                    <td>
										<textarea rows="4" cols="60" name="desc"></textarea>
                                        <span id="err_desc" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr>                                  
                                </tbody>                                
                                </table>
                        </div>                           
                    </div>              
                    <div class="pull-right">
                        <input class="btn btn-primary" data-toggle="tooltip" type="submit" value="保存">
                    </div>
			    </form><!--表单数据-->
                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
</div>
<script>  

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
