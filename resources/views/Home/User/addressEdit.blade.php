<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <meta http-equiv="keywords" content="" />
    <meta name="description" content="" />
    <link rel="stylesheet" href="{{asset('Static/css/index.css')}}" type="text/css">
    <script src="{{asset('Static/js/jquery-1.10.2.min.js')}}"></script>
    <script src="{{asset('Static/js/slider.js')}}"></script>
    <script src="{{asset('js/global.js')}}"></script>
	<script src="/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
</head>
<style type="text/css">
.wi80-BFB{width:80%}
.wi40-BFB{width:40%}
.seauii{ padding:7px 10px; margin-right:10px}
.he110{ height:110px}
.di-bl{ display:inherit}
</style>
<body>
<div class="adderss-add">
    <div class="ner-reac ol_box_4" style="visibility: visible; position: fixed; z-index: 500; width: 100%; height:100%">
        <div class="box-ct">
            <div class="box-header">
                <!-- <a href="" class="box-close"></a> -->
                <span class="box-title">添加地址</span>
            </div>
            <form id="form" >
                <table width="90%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="right"><span class="xh">*</span>收货人：&nbsp;</td>
                        <td><input class="wi80-BFB" name="consignee" type="text" value="{{$address->consignee}}" maxlength="12" /></td>
                    </tr>
                    <tr>
                        <td align="right"><span class="xh">*</span>收货地址：&nbsp;</td>
                        <td>
                            <select class="di-bl fl seauii" name="province" id="province" onChange="get_city(this,'{{url('/getCities')}}')">
                                <option value="0">请选择</option>
                            @foreach($prolist as $p)
                                <option value="{{$p->id}}" @if($address->province == $p->id) selected @endif >{{$p->name}}</option>
                            @endforeach
                            </select>

                            <select class="di-bl fl seauii" name="city" id="city" onChange="get_area(this,'{{url('/getAreas')}}')">
                                <option value="{{$address->city}}">{{$address->city_name}}</option>
                            @foreach($citylist as $region)
                                <option  value="{{$region->id}}" @if($address->city == $region->id) selected @endif>{{$region->name}}</option>
                            @endforeach
                            </select>

                            <select class="di-bl fl seauii" name="area" id="area" onChange="get_town(this,'{{url('/getTowns')}}')">
                                <option value="{{$address->area}}">{{$address->area_name}}</option>
                            @foreach($arealist as $region)
                                <option  value="{{$region->id}}" @if($address->area == $region->id) selected @endif>{{$region->name}}</option>
                            @endforeach
                            </select>
                            
                            <select class="di-bl fl seauii" name="town" id="town" >
                                <option value="{{$address->town}}">{{$address->town_name}}</option>
                            @foreach($townlist as $region)
                                <option  value="{{$region->id}}" @if($address->town == $region->id) selected @endif>{{$region->name}}</option>
                            @endforeach
                            </select>
                            <br>
                        </td>
                    </tr>
                    <tr>
                    	<td align="right" valign="top"><span class="xh">*</span>详细地址：&nbsp;</td>
                    	<td><textarea class="he110 wi80-BFB re-no" name="address" id="address" placeholder="详细地址" maxlength="100">{{$address->address}}</textarea></td>
                    </tr>
                    <tr>
                        <td align="right">邮编：&nbsp;</td>
                        <td><input class="wi80-BFB" type="text" name="zipcode" value="{{$address->zipcode}}" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" maxlength="10"/></td>
                    </tr>
                    <tr>
                        <td align="right"><span class="xh">*</span>手机：&nbsp;</td>
                        <td><input class="wi40-BFB" type="text" name="mobile" value="{{$address->mobile}}" onpaste="this.value=this.value.replace(/[^\d-]/g,'')" onKeyUp="this.value=this.value.replace(/[^\d-]/g,'')" maxlength="15"/></td>
                    </tr>
                    <tr>
                        <td class="pa-50-0">&nbsp;</td>
                        <td align="right">
                            <button type="submit" class="box-ok ma-le--70"><span>保存收货地址</span></button>
                        </td>    
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
function checkForm(){
    var consignee = $('input[name="consignee"]').val();
    var province = $('select[name="province"]').find('option:selected');
    var city = $('select[name="city"]').find('option:selected');
    var area = $('select[name="area"]').find('option:selected');
    var town = $('select[name="town"]').find('option:selected');
    var address = $('textarea[name="address"]').val();
    var mobile = $('input[name="mobile"]').val();
    var error = '';
    if(consignee == ''){
        error = '收货人不能为空！<br/>';layer.alert(error, {icon: 2});return false;
    }
    if(province.val()==0){
        error = '请选择省份！<br/>';layer.alert(error, {icon: 2});return false;
    }
    if(city.val()==0){
        error = '请选择城市！<br/>';layer.alert(error, {icon: 2});return false;
    }
    if(area.val()==0){
        error = '请选择区域！<br/>';layer.alert(error, {icon: 2});return false;
    }
    if(address == ''){
        error = '请填写地址！<br/>';layer.alert(error, {icon: 2});return false;
    }
    if(!checkMobile(mobile)){
        error = '手机号码格式有误！<br/>';layer.alert(error, {icon: 2});return false;
    }
    var arr = $('#form').serializeArray();
    var data = {};
    $.each(arr,function(index,item){
        data[item.name]=item.value;
    });
    data['province_name']= province.text();
    data['city_name']= city.text();
    data['area_name']= area.text();
    if(town.val() != 0){
        data['town_name']= town.text();
    }
    return JSON.stringify(data);
}
</script>
</body>
</html>
