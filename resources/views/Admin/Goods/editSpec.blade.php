@include('Admin.Public.min-header')
<div class="wrapper">
    @include('Admin.Public.breadcrumb')
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
            	<a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/1008/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 编辑商品规格</h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">商品规格</a></li>
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="addEditSpecForm" action="{{ url('admin/Goods/specList/'.$spec[0]->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                        <!--通用信息-->
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>规格名称：</td>
                                    <td>
                                        <input type="text" value="{{ $spec[0]->name }}" name="name"/>
                                    </td>
                                </tr>  
                                <tr>
                                    <td>所属商品类型：</td>
                                    <td>
                                        <select name="type_id" id="type_id">
                                             <option value="">请选择</option>
                                            @foreach($type as $v)
                                             <option value="{{ $v->id }}">{{ $v->name }}</option>
                                            @endforeach                                       
                                        </select>
                                    </td>
                                </tr>  
                                
                                <tr>
                                    <td>能否进行检索：</td>
                                    <td>
                                        <input type="radio" value="0" name="search_index">不需要检索
                                        <input type="radio" value="1" name="search_index">关键字检索
                                    </td>
                                </tr>  
                                <tr>
                                    <td>规格项：</td> 
                                    <td>
                                    <textarea rows="5" cols="30" name="items">{{$items}}</textarea>
									多个规格项用英文‘,’分隔
                                    </td>
                                </tr>       
                                <tr>
                                    <td>排序：</td>
                                    <td>
                                        <input type="text" value="{{ $spec[0]->order }}" name="order"/>
                                    </td>
                                </tr>                                                           
                                </tbody>                                
                                </table>
                        </div>                           
                    </div>              
                    <div class="pull-right">
                        <button class="btn btn-primary" title="" data-toggle="tooltip" type="submit" data-original-title="保存"><i class="fa fa-save"></i></button>
                    </div>
			    </form><!--表单数据-->
                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
</div>
</body>
</html>
