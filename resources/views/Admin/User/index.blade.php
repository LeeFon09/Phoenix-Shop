{{--<include file="Public/min-header"/>--}}
@include('Admin.Public.min-header')
<div class="wrapper">
  {{--<include file="Public/breadcrumb"/>--}}
    @include('Admin.Public.breadcrumb')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 用户列表</h3>
                </div>
                <div class="panel-body">
                    <div class="navbar navbar-default">
                            <form action="" id="search-form2" class="navbar-form form-inline" method="post" onsubmit="return false">
                                <div class="form-group">
                                    <label class="control-label" for="input-mobile">手机号码</label>
                                    <div class="input-group">
                                        <input type="text" name="mobile" value="" placeholder="手机号码" id="input-mobile" class="form-control">
                                        <!--<span class="input-group-addon" id="basic-addon2"><i class="fa fa-search"></i></span>-->
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="input-email">email</label>
                                    <div class="input-group">
                                        <input type="text" name="email" value="" placeholder="email" id="input-email" class="form-control">
                                        <!--<span class="input-group-addon" id="basic-addon2"><i class="fa fa-search"></i></span>-->
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <input type="hidden" name="order_by" value="user_id">
                                	<input type="hidden" name="sort" value="desc">
                                	<button type="submit" onclick="keywords()" id="button-filter search-order" class="btn btn-primary pull-right"><i class="fa fa-search"></i> 筛选</button>
                                     {{--ajax_get_table('search-form2',1)--}}
                                 </div>
                                {{--<button type="button" onclick="send_message(0);" class="btn btn-primary"><i class="fa"></i> 发送站内信</button>--}}
                                <button type="button" onclick="send_mail(0);" class="btn btn-primary"><i class="fa"></i> 发送邮箱</button>
                                @if(in_array('user',session('url')) || session('admin')->admin_id == '1')
								 <a href="{{url('admin/User/create')}}" class="btn btn-info pull-right">添加会员</a>
                                @endif
                            </form>
                    </div>
                    {{--<div id="ajax_return">--}}

                    {{--</div>--}}
                    <div class="col-sm-12">
                        <table id="list-table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                            <thead>
                            <tr role="row" align="center">
                                <th class="sorting" tabindex="0">ID</th>
                                <th class="sorting" tabindex="0" width="10%">会员昵称</th>
                                <th class="sorting" tabindex="0">会员等级</th>
                                <th class="sorting" tabindex="0">累计消费</th>
                                <th class="sorting" tabindex="0">邮件地址</th>
                                <th class="sorting" tabindex="0">手机号码</th>
                                <th class="sorting" tabindex="0">余额</th>
                                <th class="sorting" tabindex="0">积分</th>
                                <th class="sorting" tabindex="0">注册日期</th>
                                @if(in_array('user',session('url')) || session('admin')->admin_id == '1')
                                <th class="sorting" tabindex="0">操作</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                <tr role="row" align="center">
                                    <td>
                                        <label><input type="checkbox" name="selected" value="{{$d->user_id}}">{{$d->user_id}}</label>
                                    </td>
                                    <td>{{$d->nickname}}</td>
                                    <td>
                                        @foreach($level as $l)
                                            @if($l->level_id == $d->level)
                                                {{$l->level_name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{$d->total_amount}}</td>
                                    <td>{{$d->email}}</td>
                                    <td>{{$d->mobile}}</td>
                                    <td>{{$d->user_money}}</td>
                                    <td>{{$d->pay_points}}</td>
                                    <td>{{date('Y-m-d H:i:s', $d->reg_time)}}</td>
                                    @if(in_array('user',session('url')) || session('admin')->admin_id == '1')
                                    <td>
                                        <a class="btn btn-primary" href="{{url('admin/User/userEdit/'.$d->user_id)}}"><i class="fa fa-pencil"></i></a>
                                        <a class="btn btn-danger" href="javascript:void(0)" data-url="{{url('admin/User/userDel')}}" data-id="{{$d->user_id}}" onclick="delfun(this)"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div align="right" name="pages">
                            @if(!empty($pageObj))
                                {!! $pageObj->show() !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    function delfun(obj){
        if(confirm('确认删除')){
            $.ajax({
                type : 'post',
                url : $(obj).attr('data-url'),
                data : {user_id:$(obj).attr('data-id'),'_token':'{{csrf_token()}}'},
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

    //筛选
    function keywords()
    {
        var mobile = $("input[name='mobile']").val();
        var email = $("input[name='email']").val();
        if(mobile == '' && email == ''){
            location.href = location.href;
            return false;
        }else if(mobile != '' && email != ''){
            location.href = location.href;
            return false;
        }else if(mobile != ''){
            $.ajax({
                type: 'post',
                url: "{{url('admin/User/search')}}",
                data: {'_token': '{{csrf_token()}}', 'mobile': mobile},
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        $('div[name="pages"]').empty();
                        $("tbody").empty();
                        $("tbody").append(data.msg);
                    } else {
                        layer.msg(data.msg, {icon: 5});
                    }
                }
            });
        }else if(email != ''){
            $.ajax({
                type: 'put',
                url: "{{url('admin/User/search')}}",
                data: {'_token': '{{csrf_token()}}', 'email': email},
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        $('div[name="pages"]').empty();
                        $("tbody").empty();
                        $("tbody").append(data.msg);
                    } else {
                        layer.msg(data.msg, {icon: 5});
                    }
                }
            });
        }else{
            console.log(email);
        }
    }
</script>

<script>
    $(document).ready(function(){
        ajax_get_table('search-form2',1);
    });

    //发送站内信
    function send_message(id)
    {
        var obj = $("input[name*='selected']");
        var url = "{:U('Admin/User/sendMessage')}";
        if(obj.is(":checked")){
            var check_val = [];
            for(var k in obj){
                if(obj[k].checked)
                    check_val.push(obj[k].value);
            }
            url += "?user_id_array="+check_val;
        }
        layer.open({
            type: 2,
            title: '站内信',
            shadeClose: true,
            shade: 0.8,
            area: ['580px', '480px'],
            content: url
        });
    }

    //发送邮件
    function send_mail(id)
    {
        var obj = $("input[name*='selected']");
        var url = "{{url('admin/User/sendMail')}}";
        if(obj.is(":checked")){
            var check_val = [];
            for(var k in obj){
                if(obj[k].checked)
                    check_val.push(obj[k].value);
            }
            url += "?user_id_array="+check_val;
            layer.open({
                type: 2,
                title: '发送邮箱',
                shadeClose: true,
                shade: 0.8,
                area: ['580px', '480px'],
                content: url
            });
        }else{
            layer.msg('请选择会员');
        }

    }

    /**
     * 回调函数
     */
    function call_back(v) {
        layer.closeAll();
        if (v == 1) {
            layer.msg('发送成功');
        } else {
            layer.msg('发送失败');
        }
    }


</script>
</body>
</html>
