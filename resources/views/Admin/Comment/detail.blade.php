@include('Admin.Public/min-header')
<div class="wrapper">
  @include('Admin/Public/breadcrumb')
    <!-- Main content -->
    <section class="content">
    @if (session('info'))
        <div class="alert alert-success">
          {{ session('info') }}
        </div>
    @endif
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-list"></i> 评论回复
                        <a data-original-title="返回" class="btn btn-default pull-right" style="margin-top:-8px;" title="" data-toggle="tooltip" href="javascript:history.go(-1)"><i class="fa fa-reply"></i></a>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                    <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <!-- DIRECT CHAT PRIMARY -->
                            <div class="box direct-chat direct-chat-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">用户评论</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <!-- Conversations are loaded here -->
                                    <div class="">
                                        <!-- Message. Default to the left -->
                                    @foreach($comment as $v)
                                        @if($v->parent_id == 0)
                                        <div class="direct-chat-msg">
                                            <div class="direct-chat-info clearfix">

                                                <span class="direct-chat-name pull-left"><!--用户名 --></span>
                                                <span class="direct-chat-timestamp pull-right">{{date('Y-m-d H:i:s',$v->add_time)}}</span>
                                            </div><!-- /.direct-chat-info -->
                                            <img alt="用户" src="{{ url('dist/img/user2-160x160.jpg') }}" class="direct-chat-img"><!-- /.direct-chat-img -->
                                            <div class="direct-chat-text">
                                                 {{$v->content}}
                                            </div><!-- /.direct-chat-text -->
                                        </div><!-- /.direct-chat-msg -->

                                        @else
                                        <div class="direct-chat-msg right">
                                            <div class="direct-chat-info clearfix">
                                                <span class="direct-chat-name pull-right"><!--管理员 --></span>
                                                <span class="direct-chat-timestamp pull-left">{{date('Y-m-d H:i:s',$v->add_time)}}</span>
                                            </div><!-- /.direct-chat-info -->
                                            <img alt="管理员" src="{{ url('dist/img/user2-160x160.jpg') }}" class="direct-chat-img"><!-- /.direct-chat-img -->
                                            <div class="direct-chat-text">
                                                 {{$v->content}}
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach                                                                              
                                    </div> 
                                    <!-- /.direct-chat-pane -->
                                </div><!-- /.box-body -->
                                <!-- /.box-footer-->
                            </div><!--/.direct-chat -->
                        </div>       
                        <div class="col-md-2"></div>
                     <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                <form method="post" action="{{ url('admin/comment/reply/'.$comment[0]->comment_id) }}">
                                {{ csrf_field() }}
                                    <textarea class="form-control" rows="3" placeholder="请输入回复内容" name="content"></textarea>                                
	             					<div class="form-group"><input type="submit" class="btn btn-primary pull-right margin" value="回复"></div>
                                </form>            
                                </div>
                                <div class="col-md-2"></div>   
                                                                                             
                                </div>
                          </div>
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
</script>
</body>
</html>
