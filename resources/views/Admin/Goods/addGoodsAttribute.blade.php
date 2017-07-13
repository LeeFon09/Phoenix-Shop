@include('Admin.Public/min-header')
<div class="wrapper">
  @include('Admin/Public/breadcrumb')
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
            	<a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/1009/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 添加商品属性</h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">商品属性</a></li>
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="addEditGoodsAttributeForm" action="{{ url('admin/Goods/goodsAttributeList') }}"> 
                    {{ csrf_field() }}                   
                        <!--通用信息-->
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>属性名称：</td>
                                    <td>
                                        <input type="text" value="{{ old('attr_name') }}" name="attr_name"/>
                                        <span id="err_attr_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr>  
                                <tr>
                                    <td>所属商品类型：</td>
                                    <td>
                                        <select name="type_id" id="type_id">
                                            <option value="">请选择</option>
                                            @foreach($goodsType as $v)
                                             <option value="{{$v->id}}">{{$v->name}}</option>
                                            @endforeach                                       
                                        </select>                                        
                                    </td>
                                </tr>  
                                <tr>
                                    <td>检索方式：</td>
                                    <td>
                                        <input type="radio" value="0" name="attr_index">不需要检索
                                        <input type="radio" value="1" name="attr_index">关键字检索
                                        <input type="radio" value="2" name="attr_index">范围检索
                                    </td>
                                </tr>  
                                
                                <tr>
                                    <td>该属性值的录入方式：</td>
                                    <td>
                                        <input type="radio" value="0" name="attr_input_type">手工录入
                                        <input type="radio" value="1" name="attr_input_type">从下面的列表中选择（多个可选值用英文‘,’隔开）
                                        <input type="radio" value="2" name="attr_input_type">多行文本框                                     
                                    </td>
                                </tr>  
                                <tr>
                                    <td>可选值列表：</td> 
                                    <td>
                                    <textarea rows="5" cols="30" name="attr_values"></textarea>
                                    录入方式为手工或者多行文本时，此输入框不需填写。
                                    </td>
                                </tr>                                
                                </tbody>                                
                                </table>
                        </div>                           
                    </div>              
                    <div class="pull-right">
                        <button class="btn btn-primary" title="" data-toggle="tooltip" type="submit"  data-original-title="保存"><i class="fa fa-save"></i></button>
                    </div>
			    </form><!--表单数据-->
                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
</div>
</body>
</html>
