{{--<include file="Public/min-header"/>--}}
@include('admin.Public.min-header')
<div class="wrapper">
    {{--<include file="Public/breadcrumb"/>--}}
    @include('admin.Public.breadcrumb')
	<section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">增加分类</h3>
                        </div>
                        <!-- /.box-header -->
                        <form action="{{url('admin/Article/categoryHandle')}}" method="post" class="form-horizontal">
                            {{csrf_field()}}
                        <div class="box-body">                         
                                <div class="form-group">
                                    <label class="control-label col-sm-2">分类名称</label>
                                    <div class="col-sm-4">
                                        <input type="text" placeholder="名称" class="form-control" style="width:200px" name="cat_name" >
                                    </div>
                                    <div class="col-sm-4"><span class="help-inline text-warning">这将是该分类在站点上显示的名字。</span></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">所属分组</label>
                                    <div class="col-sm-4">
                                    	<input type="radio" name="cat_type" checked value="0">默认
                                        <input type="radio" name="cat_type"  value="1">系统帮助
                                        <input type="radio" name="cat_type"  value="2">系统公告
                                    </div>
                                    <div class="col-sm-4"><span class="help-inline text-warning">方便前台区分调用系统发布公告和系统帮助类文章。</span></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">上级分类</label>

                                    <div class="col-sm-8">
                                        <select class="small form-control"  style="width:200px"  tabindex="1" name="parent_id">
                                            <option value="0">顶级分类</option>
                                            @foreach($arr as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
										</select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">导航显示</label>
									
                                    <div class="col-sm-8">
                              			<label> <input type="radio" name="show_in_nav" checked value="1"> 是</label>
                              			<label> <input type="radio" name="show_in_nav" value="0"> 否</label>
                                    </div>
                                </div>
                               <div class="form-group">
                                    <label class="control-label col-sm-2">显示排序</label>

                                    <div class="col-sm-8">
                                        <input type="text" placeholder="50" class="form-control"  style="width:200px"  name="sort_order" >
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                               <div class="form-group">
                                    <label class="control-label col-sm-2">搜索关键字</label>

                                    <div class="col-sm-8">
                                        <input type="text" placeholder="关键字" class="form-control" style="width:400px"  name="keywords" >
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">搜索描述</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="描述" class="form-control" style="width:400px"  name="cat_desc" >
                                        <span class="help-inline"></span>
                                    </div>
                                </div>
                        </div>
                        <div class="box-footer">
                        	<button type="reset" class="btn btn-primary"><i class="icon-ok"></i>重填  </button>                       	                 
                            <button type="submit" class="btn btn-primary pull-right"><i class="icon-ok"></i>提交  </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
</div>  
</body>
</html>