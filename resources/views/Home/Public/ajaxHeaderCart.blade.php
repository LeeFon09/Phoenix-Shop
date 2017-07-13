@if(count($cateList) == 0)
    <div class="micart-about">
        <span class="micart-xg">您的购物车是空的，赶紧选购吧！</span>
    </div>
@endif
<div class="commod" style="overflow: auto;max-height:400px">
    <ul>
    @foreach($cateList as $k => $v)
        <li class="goods">
            <div>
                <div class="p-img">
                    <a href=""> <img src="{{url($v[0]->original_img)}}" alt=""> </a>
                </div>
                <div class="p-name">
                    <a href=""> <span class="p-slogan">{{$v[0]->goods_name}}</span>
                        <span class="p-promotions hide"></span> </a>
                </div>
                <div class="p-status">
                    <div class="p-price">
                        <b>¥&nbsp;{{$v[0]->price or $v[0]->shop_price}}</b> <em>x</em> <span>{{$v[0]->num}}</span>
                    </div>
                    <div class="p-tags"></div>
                </div>
                <a href="javascript:;" class="icon-minicart-del" title="删除" onclick="del({{$k}})">删除</a>
            </div>
        </li>
    @endforeach
    </ul>
</div>

<div class="settle">
    <p>共<em class="J_Cart_Number">{{$number}}</em>件商品，金额合计<b>¥&nbsp;{{$total}}</b></p>

    <a class="js-button" href=" @if(count($cateList)>0) {{url('home/cart/cart') }}@endif">去结算</a>
</div>

<script>
    function del(k)
    {
        $.get("{{url('ajax/header/del')}}", {"session_id":k}, function(data){

            $.get('{{url("ajax/header/cart")}}',function(data){
                $("#header_cart_list > .micart > .ris").empty().html(data);
                $('#cart_quantity').html($(".J_Cart_Number").html());
            });
        });
    }
</script>

