@extends('layouts.home')

@section('title','收货地址')

@section('content')
<div class="layout pa-to-10 fo-fa-ar">
    <!--菜单-->
    {{--<include file="User/menu" />--}}
    @include('Home.User.menu')
    <!--菜单-->
    <div class="fr wi940">
        <div class="he50 wddd">
            <div class="fl ddd-h2">
                <h2><span>收货地址管理</span></h2>
            </div>
            <div class="fr ddd-h2">
            	<h2 style="cursor: pointer;"><a class="co-red" onClick="address_add()">添加地址</a></h2>
            </div>
        </div>

        <div class="wddd-js ov-in">
            <div class="list-group-title">
                <table class="merge-tab" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="col-pro wi137">收货人</th>
                        <th class="">收货地址</th>
                        <th class="wi118">邮编</th>
                        <th class="wi172">手机/电话</th>
                        <th class="col-operate wi199">操作</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="merge-list">
                @if(empty($lists))
                <p style="text-align:center"><img src="{{URL::asset('Static/images/null_data.jpg')}}" width="400"  /></p>
                @endif
                <div class="ma-0--1">
                    <div class="o-pro">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                        @foreach($lists as $list)
                        <tr>
                            <td class="col-pro wi137">{{$list->consignee}}</td>
                            <td class="">
                                <span>
                                    {{$list->province_name}}，
                                    {{$list->city_name}}，
                                    {{$list->area_name}}，
                                    {{$list->town_name}}，<br/>
                                    {{$list->address}}
                                </span>
                            </td>
                            <td class="wi118">{{$list->zipcode}}</td>
                            <td rowspan="1" class="wi172"><span>{{$list->mobile}}</span></td>
                            <td rowspan="1" class="col-operate wi199">
                                <p class="p-link  pfc">
                                    <a href="javascript:address_edit({{$list->address_id}})">编辑</a></p>
                                <p class="p-link  pfc">
                                    <a href="{{url('home/user/address',['id'=>$list->address_id])}}">删除</a></p>
                                @if($list->is_default == 0)
                                <p class="p-link  pfc">
                                    <a class="red" href="{{url('home/user/addressSetDefault',['id'=>$list->address_id])}}">设为默认</a></p>
                                @else
                                    <p class="p-link  pfc"><span style="color:red;font-weight: bold;">默认地址</span></p>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="he80"></div>
@stop

@section('javascript')
<script>
/**
 * 新增收货地址
 */
function address_add(){
    var url = '{{url('home/user/address/create')}}';	// 新增地址
    layer.open({
        type: 2,
        title: '添加收货地址',
        shadeClose: true,
        shade: 0.8,
        btn:['保存','取消'],
        zIndex:500,
        area: ['800px', '500px'],
        content: [url,'no'],
        yes:function(index,layerobj){
            var data = $(layerobj).find("iframe")[0].contentWindow.checkForm();
            if(data != undefined){
                $.ajax({
                    type : "POST",
                    data : {"_token":"{!! csrf_token() !!}","data":data},
                    url  : '{!! url("home/user/address") !!}',
                    error: function(json) {
                        layer.alert(json.info, {icon: 2});
                        return;
                    },
                    success: function(json) {
                        layer.alert(json.info, {icon: 1});
                        layer.closeAll(); // 关闭窗口
                        location.reload();
                    }
                });
            }
        }
    });
}
/**
 * 修改收货地址
 * @param id
 */
function address_edit(id) {
    var url = '{!!url("home/user/address/'+id+'/edit")!!}';
    layer.open({
        type: 2,
        title: '修改收货地址',
        shadeClose: true,
        shade: 0.8,
        btn:['保存','取消'],
        zIndex:500,
        area: ['800px', '500px'],
        content: [url,'no'],
        yes:function(index,layerobj){
            var data = $(layerobj).find("iframe")[0].contentWindow.checkForm();
            if(data != undefined) {
                $.ajax({
                    type: "PUT",
                    data: {"_token": "{{csrf_token()}}", "data": data},
                    url: '{{url("home/user/address")}}/' + id,
                    error: function (json) {
                        layer.alert(json.info, {icon: 2});
                        return;
                    },
                    success: function (json) {
                        layer.alert(json.info, {icon: 1});
                        layer.closeAll(); // 关闭窗口
                        location.reload();
                    }
                });
            }
        }
    });
}
</script>
@stop