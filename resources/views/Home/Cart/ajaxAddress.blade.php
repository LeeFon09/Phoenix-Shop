@foreach($lists as $v)
    @if($v->is_default == 1)<!--默认选中的地址-->
    <div class="order-address-list address_current">
        <table width="100%" cellspacing="0" cellpadding="0" border="1" class="address">
            <tbody>
            <tr>
                <td width="60" class="default"><i></i></td>
                <td width="60" class="co-red default">寄送至</td>
                <td width="60" class="address_id">
                    <input type="radio" onclick="swidth_address(this)" name="address_id" value="{{$v->address_id}}" checked="checked"/>
                </td>
                <td width="160" class="consignee"><b>收货人:{{$v->consignee}}</b></td>
                <td width="300" class="address2"><span>地址:{{$v->province_name}} {{$v->city_name}} {{$v->area_name}} {{$v->town_name}}<br/>{{$v->address}}</span>
                </td>
                <td width="100" class="mobile"><span>邮编：{{$v->zipcode}}</span></td>
                <td width="160" class="mobile"><span>电话：{{$v->mobile}}</span></td>
                <td width="60" class="wi100"><span style="color:red;">默认地址</span></td>
                <td width="60" class="wi100"><span><a href="javascript:address_edit({{$v->address_id}});">修改</a></span>
                </td>
                <td><a onclick="del_address({{$v->address_id}});">
                        <div class="gwc-gb ma-ri-20"></div>
                    </a></td>
            </tr>
            </tbody>
        </table>
    </div>
    @else
    <div class="order-address-list">
        <table width="100%" cellspacing="0" cellpadding="0" border="1" class="address">
            <tbody>
            <tr>
                <td width="60" class="default"><i></i></td>
                <td width="60" class="co-red default">寄送至</td>
                <td width="60" class="address_id">
                    <input type="radio"  onclick="swidth_address(this)" name="address_id" value="{{$v->address_id}}"/>
                </td>
                <td width="160" class="consignee"><b>收货人:{{$v->consignee}}</b></td>
                <td width="300" class="address2">
                    <span>地址:{{$v->province_name}} {{$v->city_name}} {{$v->area_name}} {{$v->town_name}}<br/>{{$v->address}}</span>
                </td>
                <td width="100" class="mobile"><span>邮编：{{$v->zipcode}}</span></td>
                <td width="160" class="mobile"><span>电话：{{$v->mobile}}</span></td>
                <td width="60" class="wi100"><span>&nbsp;&nbsp;</span></td>
                <td width="60" class="wi100">
                    <span><a href="javascript:address_edit({{$v->address_id}});">修改</a></span></td>
                <td><a onclick="del_address({{$v->address_id}});">
                        <div class="gwc-gb ma-ri-20"></div>
                    </a></td>
            </tr>
            </tbody>
        </table>
    </div>
    @endif
@endforeach