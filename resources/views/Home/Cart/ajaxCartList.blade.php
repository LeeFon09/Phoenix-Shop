
@if(empty($all))
     <p style="text-align:center"><a href="{{url('/')}}"><img src="{{url('Static/images/null_cart2.jpg')}}"  /></a></p>
     <script>
	    $(".sc-acti-list,.sc-pro-list").hide();
     </script>
 @endif

<div class="sc-pro-list">
  <table width="100%" border="0" cellspacing="0" cellpadding="1">
      <tr class="ba-co-danhui">
        <th class="pa-le-9" align="center" valign="middle">&nbsp;&nbsp;</th>
        <th align="center" valign="middle" colspan="2">商品</th>
        <th align="center" valign="middle">市场价（元）</th>                        
        <th align="center" valign="middle">单价（元）</th>
        {{--<if condition="($user[discount] neq 1) and ($user[discount] neq null)">--}}
	        {{--<th align="center" valign="middle">会员折扣价</th>        --}}
        {{--</if>--}}
        <th align="center" valign="middle">数量</th>
        <th align="center" valign="middle">小计（元）</th>
        <th align="center" valign="middle">操作</th>
      </tr>

     @foreach($all as $k=>$v)
      <tr>
        <td class="pa-le-9" style="border-right:0" align="center" valign="middle">    
            <input type="checkbox"  name="cart_select[{{$k}}]" @if($selected[$k] == 1) checked="checked" @endif value="{{$k}}" onclick="ajax_cart_list({{$k}})" />
        </td>
        <td style="border-left:0px;;border-right:0px" class="pa-to-20 pa-bo-20 bo-ri-0" width="80px" align="center" valign="top" valign="middle">
            <a class="gwc-wp-list di-bl wi63 hi63" href="{{url('home/goods/goodsinfo',['id'=>$v[0]->goods_id])}}">
                <img class="wi63 hi63" src="{{url($v[0]->original_img)}}" />
            </a>
        </td>
        <td style="border-left:0px; border-right:0px"  class="pa-to-20 wi516"align="left"  valign="top" valign="middle">
            <p class="gwc-ys-pp">
            	<a href="{{url('home/goods/goodsinfo',['id'=>$v[0]->goods_id])}}" style="vertical-align:middle">{{$v[0]->goods_name}}</a>
                {{--<!--团购--><if condition="$v[activity_type] eq 2"><img  width="80" height="60" src="/Public/images/groupby2.jpg" style="vertical-align:middle"></if>--}}
                {{--<!--抢购--><if condition="$v[activity_type] eq 1"><img  width="40" height="40" src="/Public/images/qianggou2.jpg" style="vertical-align:middle"></if>                --}}
            </p>
            <p class="ggwc-ys-hs">{{$v[0]->key_name or "发财"}}</p>
        </td>
        <td style="border-left:0px" align="center" valign="middle"><span>￥{{$v[0]->market_price}}</span></td>
        <td style="border-left:0px" align="center" valign="middle"><span>￥{{$v[0]->price or $v[0]->shop_price}}</span></td>
        {{--<if condition="($user[discount] neq 1) and ($user[discount] neq null)">--}}
        {{--<td style="border-left:0px" align="center" valign="middle"><span>￥{$v.member_goods_price}</span></td>        --}}
        {{--</if>        --}}
        <td align="center" valign="middle">
            <div class="sc-stock-area">
                <div class="stock-area">                            
                    <a onClick="switch_num(-1,{{$k}},{{$v[0]->store_count}},{{$k}});" title="减">-</a>
                    <input class="wi43 fl" type="text" value="{{$v[0]->goods_num}}" name="goods_num[{{$k}}]" id="goods_num[{{$k}}]" readonly="" />
                    <a onClick="switch_num(1,{{$k}},{{$v[0]->store_count}},{{$k}});" title="加">+</a>
                </div>
            </div>
        </td>
        <td align="center" valign="middle">￥{{$v[0]->subtotal}}</td>
        <td align="center" valign="middle"><a  class="gwc-gb" id="del" href="javascript:void(0);" onclick="del({{$k}})"></a></td>
      </tr>
    @endforeach
    </table>
</div>



<div class="sc-total-list ma-to-20 sc-pro-list">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pa-le-28 gwx-xm-dwz">
            <input type="checkbox" name="select_all" id="select_all"    @if($sel_all)  checked="checked" @endif   onchange="check_all({{$sel_all}});"   value="1"/>
            <label for="select_all">全选</label>
            <a href="javascript:void(0);" onclick="del_cart_more();">删除选中商品</a>
        </td>
        <td width="190" align="right">总计金额：</td>
        <td width="69" align="right">￥{{$total or '0'}}</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td id="bo-to-dedede" width="190" align="right">共节省：</td>
        <td id="bo-to-dedede" width="69" align="right">￥{{$priceSpread}}</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td id="bo-to-dedede" width="190" align="right">合计（不含运费）：</td>
        <td id="bo-to-dedede" width="69" align="right"><em>￥{{$total or '0'}}</em></td>
        <td>&nbsp;</td>
      </tr>
    </table>
</div>

<script>

    function del(k)
    {
            layer.confirm('您确定要删除么', {
                btn: ['确定','取消'] //按钮
            }, function(){
                ajax_del_cart(k);
                layer.msg("删除成功", {icon: 1});
            }, function(){

            });
    }

    /**
     * 购买商品数量加加减减
     * 购买数量 , 购物车id , 库存数量
     */
    function switch_num(num,cart_id,store_count,keys)
    {
        var num2 = parseInt($("input[name='goods_num["+cart_id+"]']").val());

        num2 += num;
        if(num2 < 1) num2 = 1; // 保证购买数量不能少于 1
        if(num2 > store_count)
        {
            error = "库存只有 "+store_count+" 件, 你只能买 "+store_count+" 件";
            layer.alert(error, {icon: 2});
            num2 = store_count; // 保证购买数量不能多余库存数量
        }

        $.post("{{url('home/cart/ajaxSession')}}",{"num":num2, "keys":keys , "_token":"{{csrf_token()}}"},
            function(data){
                if(data.status){
                    ajax_cart_list(); // ajax 更新商品价格 和数量
                }else{
                    console.log("改变数量失败");
                }

            }
        , "json");


    }


    /**  全选 反选 **/
    function check_all(sel_all)
    {
        var sel = sel_all? 0:1
        $.get("{{url('home/cart/ajaxSelAll')}}",{"sel":sel},function(data){
            if(data == 1){
                var vt = $("#select_all").is(':checked');
                $("input[name^='cart_select']").prop('checked',vt);
                // var checked = !$('#select_all').attr('checked');
                // $("input[name^='cart_select']").attr("checked",!checked);
                ajax_cart_list(); // ajax 更新商品价格 和数量
            }
        });

    }

</script>