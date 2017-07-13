@extends('layouts.home')

@section('title','个人信息')

@section('content')
<div class="layout ov-hi">
    <div class="breadcrumb-area">    
	    <foreach name="navigate_user" key="k" item="v">
	        <if condition="$k neq '首页'"> 首页> </if>
            <a href="{{url('home/user/userInfo')}}">个人信息</a>
        </foreach>
    </div>
</div>
<div class="layout pa-to-10 fo-fa-ar">
    <!--菜单-->
    @include('Home.User.menu')
    <!--菜单-->

    <div class="fr wi940">
        <div class="xgzl-w">
            <form action="" method="post">
                <dl>
                    <dd class="te-al po-re wi230 fl">
                        <img class="headpic" src="{{ $userInfo->head_pic?$userInfo->head_pic :'/Static/images/headPic.jpg'}}" />
                        <input type="hidden" name="head_pic" id="head_pic" value="{{$userInfo->head_pic}}">
                        <div class="upload" onClick="GetUploadify2(1,'head_pic','head_pic','add_img', '/home/uploadify/upload/')">上传图像</div>
                    </dd>
                    <dd class="fl ta-tldf">
                        <table style="width:700px;">
                            <tbody>
                            <tr>
                                <td class="cl_left">昵称：</td>
                                <td>
                                    <div class="dv_cell_left">
                                        <input type="text" name="nickname" class="imput_text vam" id="nickname" maxlength="20" autocomplete="off" value="{{$userInfo->nickname}}" />
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="cl_left">QQ：</td>
                                <td>
                                    <div class="dv_cell_left">
                                        <input type="text" name="qq" class="imput_text vam" id="userInfo_nickName" maxlength="20" autocomplete="off" value="{{$userInfo->qq}}" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" />
                                        <span id="msg_nickName"></span>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td class="cl_left">邮箱：</td>
                                <td>
                                    <div class="dv_cell_left">
                                        <span class="check"> {{$userInfo->email}}</span>
                                        @if($userInfo->email_validated == 0)
                                            <a href="{{url('home/user/validateEmail/step/'.$userInfo->user_id)}}">未验证(点击验证)</a>
                                        @else
                                            <a href="{{url('home/user/validateEmail/step/'.$userInfo->user_id)}}">已验证(点击修改)</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="cl_left">手机：</td>
                                <td>
                                    <div class="dv_cell_left">
                                        <span class="check">{{$userInfo->mobile}}</span>
                                        @if($userInfo->mobile_validated == 0)
                                            <a href="{{url('home/user/validateMobile/'.$userInfo->user_id)}}">未验证(点击验证)</a>
                                        @else
                                            <a href="{{url('home/user/validateMobile/'.$userInfo->user_id)}}">已验证(点击修改)</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="cl_left">密码：</td>
                                <td>
                                    <div class="dv_cell_left">
                                        <a href="{{url('home/user/changePassword')}}">更改</a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="cl_left">性别：</td>
                                <td>
                                    <input type="radio" name="sex" checked="checked" id="gd_secret" @if($userInfo->sex == 0) checked @endif value="0"> <label for="gd_secret">保密</label>
                                    <input type="radio" name="sex" id="gd_mile" style="margin-left:10px;" @if($userInfo->sex == 1) checked @endif value="1"> <label for="gd_mile">男</label>
                                    <input type="radio" name="sex" id="gd_femile" style="margin-left:10px;" @if($userInfo->sex == 2) checked @endif value="2"> <label for="gd_femile">女</label>
                                </td>
                            </tr>
                            <!--
                            <tr>
                                <td class="cl_left">生日：</td>
                                <td>
                                    <input type="text" class="imput_text vam" id="birthday" name="birthday" value="{$user.birthday}">
                                </td>
                            </tr>
							-->

                            <tr>

                                <td class="cl_left">城市地区：</td>
                                <td>

                                    <select  class="selec" name="province" id="province" onChange="get_city(this,'/getCities')" >
                                        <option value="0">请选择省份</option>
                                        @foreach($province as $v)
                                            <option @if($userInfo->province == $v->id) selected @endif value="{{$v->id}}">{{$v->name}}</option>
                                        @endforeach
                                    </select>
                                    <select  class="selec" name="city" id="city" onChange="get_area(this, '/getAreas')">
                                        <option value="0">请选择城市</option>
                                        @foreach($city as $v)
                                            <option @if($userInfo->city == $v->id) selected @endif value="{{$v->id}}">{{$v->name}}</option>
                                        @endforeach
                                    </select>
                                    <select  class="selec" name="district" id="area">
                                        <option value="0">请选择地区</option>
                                        @foreach($district as $v)
                                            <option @if($userInfo->district == $v->id) selected @endif value="{{$v->id}}">{{$v->name}}</option>
                                        @endforeach
                                    </select>
                                    <!--<select class="selec" id="sltProvince"><option value="-1">省份</option><option value="11">北京</option><option value="12">天津</option><option value="13">河北</option></select>-->
                                    <!--<select class="selec" id="sltCity"><option value="-1">城市</option></select>-->
                                </td>
                            </tr>

                            <tr>
                                <td colspan="1" align="left">
                                    <input type="submit" value="保存" class="btn_midefy" id="btn_midefy">
                                    {{--<input type="button" value="取消" class="btn_midefy" style="margin-left:20px;" id="btn_reload" onClick="">--}}
                                </td>
                            </tr>
                            </tbody></table>

                    </dd>
                </dl>
            </form>

        </div>
    </div>
</div>
<div class="he80"></div>
{{--
<script src="{{URL::asset('js/global.js')}}"></script>
<script src="{{URL::asset('js/pc_common.js')}}"></script>
 --}}
<script>

    $("#btn_midefy").click(function(){
        $.post("{{url('home/user/saveUserInfo')}}", {"_token":"{{csrf_token()}}", "data":$("form").serialize()},
            function(data){
                if(data == 1){
                    layer.alert('修改成功', {icon: 1});

                    return false;
                }else{
                    layer.alert('没有任何修改', {icon: 2})
                    return false;
                }
            }
        );
        return false;
    });


    function delimg(file,t){
        $.get(
                "/index.php?m=Admin&c=Uploadify&a=delupload",{action:"del", filename:file},function(){}
        );
        $('#head_pic').val('');
        $('#preview').attr('src','');
        $(t).remove();
    }
    function add_img(str){
        var head_pic = $('#head_pic').val();
        $('#head_pic').val(str);
        $('#preview').attr('src',str);
        $('img[class="headpic"]').attr('src',str);
//        if(!$('#delimg')){
//            $('#img_box').append('<button id="delimg" type="button" onclick="delimg('+"'"+str+"'"+',this)">删除图片</button>');
//        }else{
//            $('#delimg').attr('onclick','delimg('+"'"+str+"'"+',this)');
//        }
        if(!head_pic){
            $('#img_box').append('<button id="delimg" type="button" onclick="delimg('+"'"+str+"'"+',this)">删除图片</button>');
        }else{
            $('#delimg').attr('onclick','delimg('+"'"+str+"'"+',this)');
        }

    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var icon_wh=$(".icon_wh");
        icon_wh.each(function(){
            var thisObj=$(this);
            thisObj.bind("mouseout",function(){
                $(this).next().css("display","none");
            });
            thisObj.bind("mouseover",function(){
                $(this).next().css("display","block");
            });
        });
    });
</script>


<script>
    $(function () {
        $("#h-s").mouseover(function () {
            $('.ec-ta-x').css('left','124px');
            $('.ec-ta-x').css('width','110px');
            $(this).addClass("cullent");
        });
        $("#h-s").mouseout(function () {
            $('.ec-ta-x').css('left','0px');
            $('.ec-ta-x').css('width','124px');
            $(this).removeClass("cullent");
        });
    });
    $(function () {
        $("#q-s").mouseover(function () {
            $('.ec-ta-x').css('left','0px');
            $(this).addClass("cullent");
        });
        $("#q-s").mouseout(function () {
            $('.ec-ta-x').css('left','0px');
        });
    });
</script>
@stop