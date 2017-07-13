@include('Admin.Public/min-header')
<div class="wrapper">
  @include('Admin/Public/breadcrumb')
    <section class="content">
        @if (session('info'))
            <div class="alert alert-success">
              {{ session('info') }}
            </div>
        @endif
        <div class="row">
           <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                    	<i class="fa fa-list"></i>&nbsp;评论列表
                    </h3>
                </div>
                <div class="panel-body">
                <nav class="navbar navbar-default">	     
			        <div class="collapse navbar-collapse">
			          <form action="{{ url('admin/Comment/index') }}" id="search-form2" class="navbar-form form-inline" role="search">

			            <div class="form-group">
			              	<input type="text" class="form-control" name="content" placeholder="搜索评论内容">
			            </div>
                          <div class="form-group">
                              <input type="text" class="form-control" name="nickname" placeholder="搜索用户">
                          </div>
                          <input type="submit" value="筛选" class="btn btn-info">
			          </form>		
			      </div>
    			</nav>
                    <div id="ajax_return" align="right">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        用户
                                    </td>
                                    <td class="text-center" width="200">
                                        评论内容
                                    </td>
                                    <td class="text-center" width="300">
                                        商品
                                    </td>
                                    <td class="text-center">
                                        显示
                                    </td>
                                    <td class="text-center">
                                        评论时间
                                    </td>
                                    <td class="text-center">
                                        ip地址
                                    </td>
                                    @if(in_array('comment',session('url')) || session('admin')->admin_id == '1')
                                    <td class="text-center">操作</td>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>

                                <volist name="comment_list" id="list">
                                @foreach($comment as $v)
                                    <tr>
                                        <td class="text-center">{{ $v->username }}</td>
                                        <td class="text-left">{{ $v->content }}</td>
                                        <td class="text-left">{{ $v->goodsName }}</td>
                                        <td class="text-center">
                                            <img width="20" height="20" onclick="changeStatus(this)" data-id="{{$v->comment_id}}" data-status="{{$v->is_show}}" data-url="{{url('admin/Comment/changeStatus')}}" src="{{$v->is_show == 1 ? url('images/yes.png') : url('images/cancel.png')}}"/>
                                        </td>
                                        <td class="text-center">{{ date('Y-m-d H:i:s',$v->add_time) }}</td>
                                        <td class="text-center">{{ $v->ip_address }}</td>
                                        @if(in_array('comment',session('url')) || session('admin')->admin_id == '1')
                                        <td class="text-center">
                                            <a href="{{url('admin/comment/detail/'.$v->comment_id)}}" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-eye"></i></a>
                                            <a href="{{ url('admin/comment/del/'.$v->comment_id) }}" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </volist>

                                </tbody>
                            </table>
                        </div>
                        {!! $pageObj->show() !!}
                    </div>
                </div>

            </div>
           </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    // 删除操作
    function del(id,t)
    {
        if(!confirm('确定要删除吗?'))
            return false;
        location.href = $(t).data('href');
    }

    function op(){
        //获取操作
        var op_type = $('#operate').find('option:selected').val();
        if(op_type == 0){
			layer.msg('请选择操作', {icon: 1,time: 1000});   //alert('请选择操作');
            return;
        }
        //获取选择的id
        var selected = $('input[name*="selected"]:checked');
        var selected_id = [];
        if(selected.length < 1){

			layer.msg('请选择项目', {icon: 1,time: 1000}); //            alert('请选择项目');
            return;
        }
        $(selected).each(function(){
            selected_id.push($(this).val());
        })
        $('#op').find('input[name="selected"]').val(selected_id);
        $('#op').find('input[name="type"]').val(op_type);
        $('#op').submit();
    }

    $(document).ready(function(){
        ajax_get_table('search-form2',1);
    });


    // ajax 抓取页面
    function ajax_get_table(tab,page){
        cur_page = page; //当前页面 保存为全局变量
        $.ajax({
            type : "POST",
            url:"/index.php/Admin/Comment/ajaxindex/p/"+page,//+tab,
            data : $('#'+tab).serialize(),// 你的formid
            success: function(data){
                $("#ajax_return").html('');
                $("#ajax_return").append(data);
            }
        });
    }

    function changeStatus(obj)
    {
        $.ajax({
            type: 'post',
            url: $(obj).attr('data-url'),
            data: {
                'comment_id': $(obj).attr('data-id'),
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

</script>

</body>
</html>
