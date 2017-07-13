
<script src="{{asset('js/jquery-1.10.2.min.js')}}"></script>

<script src="{{asset('js/global.js')}}"></script>

<!--最顶部-->
{{--<include file="Public/siteTopbar" />--}}
@include('Home.Public.siteTopbar')

<!--------在线客服-------------->
{{--<include file="Public/kefu" />--}}
@include('Home.Public.kefu')
<!--------在线客服-------------->

<!--顶部banner开始-->
@if(Request::path() == "/")
<div class="top-banner" id="top-banner-block">
    <div class="top-banner-max">
        <a href="" @if($ad->target ==1) target="_blank" @endif> <img src="{{asset($ad->ad_code)}}"  title="{{$ad->ad_code}}" style="{$v[style]}"/></a>
        <a class="button-top-banner-close" href="javascript:;" title="关闭" id="top-banner-min-close" onClick="javascript:$('#top-banner-block').hide();">－</a>
    </div>
</div>

		{{--setcookie("top-banner", "1", time()+ 3600); // 弹过窗的 不在弹窗--}}
@endif

<!--顶部banner结束-->

<header>
    <div class="layout">

        <!--logo开始-->
        <div class="logo"><a href="/" title="TPshop"><img src="" alt="凤凰涅槃"></a></div>
        <!--logo结束-->

        <!-- 搜索开始-->
        <div class="searchBar">
            <div class="searchBar-form">
                <form name="sourch_form" id="sourch_form" method="post" action="{{url('Home/Goods/search')}}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="text" class="text" name="q" id="q" value=""  placeholder="  搜索关键字"/>
                    {{--<input type="text" class="text" name="q" id="q" value="123"  placeholder="  搜索关键字"/>--}}
                    <input type="button" class="button" value="搜索" onclick="$('#sourch_form').submit();"/>
                </form>
            </div>

            <div class="searchBar-hot">
                <b>热门搜索</b>
                @foreach($search as $k => $v)
                    <a target="_blank" href="{{url('home/goods/goodslist/1')}}" @if($k==0) class="ht" @endif>{{$v}}</a>
                @endforeach
            </div>
        </div>
        <!-- 搜索结束-->

        <div class="ri-mall">
            <div class="my-mall">
                <!---我的商城-开始 -->
                <div class="mall">
                    <div class="le"><a href="" >我的商城</a></div>
                </div>
                <!---我的商城-结束 -->
            </div>
            <div class="my-mall" id="header_cart_list">
                <!---购物车-开始 -->
                <div class="micart">
                    <div class="le les">
                        <a href="" >我的购物车
                            <span class="shopping-num"><em id="cart_quantity">{{$car_num}}</em><b></b></span>
                        </a>
                    </div>
                    <div class="ri ris" style=""><!-- ajax动态加载购物车 --></div>
                </div>
                <!---购物车-结束 -->

            </div>
        </div>
        <div class="qr-code">
            {{--<img src="__STATIC__/images/qrcode_vmall_app01.png" alt="二维码" />--}}
            <p>扫一扫</p>
        </div>
    </div>
</header>
{{--{{dd(session("cart"))}}--}}
<!-- 导航-开始-->
<div class="navigation">
    <div class="layout">
        <!--全部商品-开始-->
        <div class="allgoods">
            <div class="goods_num"><h2>全部商品</h2><i class="trinagle"></i></div>
            <div class="list"  @if(Request::path() == "/") style="display:block" @endif>
                <ul class="list_ul">
                    @foreach($res as $k=>$v)
                        @if($v->level == 1)
                            <li class="list-li">
                                <div class="list_a">
                                    <h3><a href="{{url('home/goods/goodslist/'.$v->id)}}"><span>{{$v->name}}</span></a></h3>
                                    <p>
                                        @for($i=0; $i<3; $i++)
                                            <a href="{{url('home/goods/goodslist/'.$v->menus[$i]->id)}}">{{$v->menus[$i]->name}}</a>
                                        @endfor
                                    </p>
                                </div>
                                <div class="list_b" data="{{$k}}">
                                    <div class="list_bigfl">

                                        @if(count($v->menus)>6)
                                            @for($i=0; $i<6; $i++)
                                                <a class="list_big_o ma-le-30" href="{{url('home/goods/goodslist/'.$v->menus[$i]->id)}}">{{$v->menus[$i]->name}}<i>＞</i></a>
                                            @endfor
                                        @else
                                            @for($i=0; $i<count($v->menus); $i++)
                                                <a class="list_big_o ma-le-30" href="{{url('home/goods/goodslist/'.$v->menus[$i]->id)}}">{{$v->menus[$i]->name}}<i>＞</i></a>
                                            @endfor
                                        @endif
                                    </div>
                                    <div class="subitems">
                                        @foreach($v->menus as $vo)
                                            @if($vo->parent_id == $v->id)
                                                <dl class="ma-to-20 cl-bo">
                                                    <dt class="bigheader wh-sp"><a href="{{url('home/goods/goodslist/'.$vo->id)}}">{{$vo->name}}</a><i>＞</i></dt>
                                                    <dd class="ma-le-100">
                                                        @foreach($vo->child as $v2)

                                                            <if condition="$v3[parent_id] eq $v2[id]">
                                                                <a class="hover-r ma-le-10 ma-bo-10 pa-le-10 bo-le-hui fl wh-sp" href="{{url('home/goods/goodslist/'.$v2->id)}}">{{$v2->name}}</a>
                                                            </if>
                                                        @endforeach
                                                    </dd>
                                                </dl>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <i class="list_img"></i>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <!--全部商品-结束-->
{{--{{dd($nav)}}--}}
        <div class="ongoods">
            <ul class="navlist">
                <li class="homepage"><a href="{{url("/")}}"><span>首页</span></a></li>

                <!-- <tpshop sql="SELECT * FROM `__PREFIX__navigation` where is_show = 1 ORDER BY `sort` DESC" key="k" item='v'>
                     <li class="page"><a href="" <if condition="$v[is_new] eq 1">target="_blank" </if><span>{$v[name]}</span></a></li>
                 </tpshop> -->

            @foreach($nav as $k=>$v)
                <li class="page"><a href="{{url($v->url)}}" @if($v->is_new==1) target="_blank" @endif > <span>{{$v->name}}</span></a></li>
            @endforeach

            </ul>
        </div>
    </div>
</div>
{{--{{dd(session("user"))}}--}}
<!-- 导航-结束-->
<script>

$(function(){

    $(".list_a").each(function(){
        $(this).mouseover(function(){
            var num = $(this).next().attr("data");
            var px = (num * -57) + "px";
            $(this).next().css("top", px);
        });
    });


//    var active_li = '{$active}';
    var active_li = 'active';
    if(active_li){
        $('li').remove('curr-res');
        $('#'+active_li).addClass('curr-res');
    }

    var uname= getCookie('user');
    {{--var uname = {{session("user")->user_id}};--}}
    if(uname == ''){
        $('.islogin').remove();
        $('.nologin').show();
    }else{
        $('.nologin').remove();
        $('.islogin').show();
        $('.userinfo').html(decodeURIComponent(uname));
    }
//        get_cart_num();
    /**
     * 鼠标移动端到头部购物车上面 就ajax 加载
     */
    var header_cart_list_over = 0;    // 判断鼠标是否移动到了上方
    $("#header_cart_list > .micart > .les").hover(function(){
        if(header_cart_list_over == 1)
            return false;
        header_cart_list_over = 1;
        $.get('{{url("ajax/header/cart")}}',function(data){
            $("#header_cart_list > .micart > .ris").empty().html(data);
            $('#cart_quantity').html($(".J_Cart_Number").html());
        });
    }).mouseout(function(){

        (typeof(t) == "undefined") || clearTimeout(t);
        t = setTimeout(function () {
            header_cart_list_over = 0; /// 标识鼠标已经离开
        }, 1000);
    });
});



    function get_cart_num(){
        var cart_cn = getCookie('cn');
        if(cart_cn == ''){
            $.ajax({
                type : "GET",
                url:"",//  /index.php?m=Home&c=Cart&a=header_cart_list//+tab,

                success: function(data){
                    cart_cn = getCookie('cn');
                    $('#cart_quantity').html(cart_cn);
                }
            });
        }
        $('#cart_quantity').html(cart_cn);
    }

</script>



