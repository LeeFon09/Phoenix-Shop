{{--<include file="Public/min-header"/>--}}
@include('Admin.Public.min-header')
<div class="wrapper">
    <!-- Content Header (Page header) -->
   {{--<include file="Public/breadcrumb"/>--}}
    @include('Admin.Public.breadcrumb')
    <section class="content">
    <!-- Main content -->
    <!--<div class="container-fluid">-->
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> 用户信息</h3>
            </div>
            <div class="panel-body">
                <form action="{{url('admin/User/userHandle')}}" method="post" onsubmit="return checkUserUpdate(this);">
                    {{csrf_field()}}
                    <input type="hidden" name="user_id" value="{{$data->user_id}}">
                    <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td class="col-sm-2">会员昵称:</td>
                        <td ><input type="text" class="form-control" name="nickname" value="{{$data->nickname}}"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>用户积分:</td>
                        <td>{{$data->pay_points}} <span style="margin-left:100px;">账户余额：{{$data->user_money}}</span></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>邮件地址:</td>
                        <td><input type="text" class="form-control" name="email" value="{{$data->email}}"></td>
                        <td>电子邮箱</td>
                    </tr>
                    <tr>
                        <td>新密码:</td>
                        <td><input type="password" class="form-control" name="password"></td>
                        <td>留空表示不修改密码</td>
                    </tr>
                    <tr>
                        <td>确认密码:</td>
                        <td><input type="password" class="form-control" name="password2" ></td>
                        <td>留空表示不修改密码</td>
                    </tr>
                    <tr>
                        <td>性别:</td>
                        <td id="order-status">
                            <input name="sex" type="radio" value="0" @if($data->sex==0) checked @endif >保密
                            <input name="sex" type="radio" value="1" @if($data->sex==1) checked @endif >男
                            <input name="sex" type="radio" value="2" @if($data->sex==2) checked @endif >女
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>QQ:</td>
                        <td>
                            <input class="form-control" type="text" name="qq" value="{{$data->qq}}">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>手机:</td>
                        <td>
                            <input type="text" class="form-control" name="mobile" value="{{$data->mobile}}">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>冻结用户:</td>
                        <td>
                            <input name="is_lock" type="radio" value="1" @if($data->is_lock==1) checked @endif >是
                            <input name="is_lock" type="radio" value="0" @if($data->is_lock==0) checked @endif >否
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>注册时间:</td>
                        <td>
                            {{date('Y-m-d H:i:s', $data->reg_time)}}
                        </td>
                        <td></td>
                    </tr>
                        
                    <tr>
                        <td>是否分销:</td>
                        <td id="order-status">                            
                            <input name="is_distribut" type="radio" value="1" @if($data->is_distribut==1) checked @endif >是
                            <input name="is_distribut" type="radio" value="0" @if($data->is_distribut==0) checked @endif >否
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" class="btn btn-info">
                                <i class="ace-icon fa fa-check bigger-110"></i> 保存
                            </button>
                            <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default pull-right" data-original-title="返回"><i class="fa fa-reply"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </form>

            </div>
        </div>
 	  </div> 
    </div>    <!-- /.content -->
   </section>
</div>
<script>
    function checkUserUpdate(){
        var email = $('input[name="email"]').val();
        var mobile = $('input[name="mobile"]').val();
        var password = $('input[name="password"]').val();
        var password2 = $('input[name="password2"]').val();

        var error ='';
        if(password != password2){
            error += "两次密码不一样\n";
        }

        if(!checkEmail(email)){
            error += "邮箱地址有误\n";
        }
        if(!checkMobile(mobile)){
            error += "手机号码填写有误\n";
        }
        if(error){
            layer.alert(error, {icon: 2});  //alert(error);
            return false;
        }
        return true;
    }
</script>

</body>
</html>