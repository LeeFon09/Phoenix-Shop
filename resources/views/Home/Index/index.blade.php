<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页--{{$title}}</title>
    <meta http-equiv="keywords" content="{$tpshop_config['shop_info_store_keyword']}" />
    <meta name="description" content="{$tpshop_config['shop_info_store_desc']}" />
</head>
<body>

{{--{{dd(password_hash("asdasd",PASSWORD_DEFAULT))}}--}}
<!--------头部开始-------------->
{{--<include file="Public/header" />--}}
@include('Home.Public.header')
<!--------头部结束-------------->
<!-- 首页轮播图片 轮播js 广告-->
<script src="{{URL::asset('Static/js/slider.js')}}"></script>
<script type="text/javascript">
    // 首页轮播图片 轮播js 广告
    $(function() {
        var bannerSlider = new Slider($('#banner_tabs'), {
            time: 5000,
            delay: 400,
            event: 'hover',
            auto: true,
            mode: 'fade',
            controller: $('#bannerCtrl'),
            activeControllerCls: 'active'
        });
        $('#banner_tabs .flex-prev').click(function() {
            bannerSlider.prev()
        });
        $('#banner_tabs .flex-next').click(function() {
            bannerSlider.next()
        });
    })
</script>

<!-- 首页轮播图片 轮播js 广告 end-->
<!--------banner-开始-------------->
<div class="nav-banner">
    <div id="banner_tabs" class="flexslider">
        <ul class="slides">
            @foreach($slideshow as $v)
                <li>
                    <a href="{{$v->ad_link}}" @if($v->target==1)>  @endif>
                        <img src="{{asset($v->ad_code)}}" width="980" height="400"  title="{{$v->ad_name}}" style=""/>
                    </a>
                </li>
            @endforeach
        </ul>
        <ol id="bannerCtrl" class="flex-control-nav flex-control-paging">
            @for($i=0; $i<2; $i++)
                <li><a>{{$i}}</a></li>
            @endfor
        </ol>
    </div>
</div>
<!--------banner-结束-------------->

{{--{{dump(session()->flush())}}--}}
<!--------热卖-首发-新闻-公告-开始-------------->
<div class="layout">
    <!--热卖开始-->
    <div class="bs-le">
        <div class="first">
            <ul>
                @foreach($hotgoods as $v)
                    <li class="sellers">
                        <div class="best">
                            <div class="best-about">
                                <div class="best_img" style="margin-top:50px; height:220px;">
                                    <a href='{{url("home/goods/goodsinfo/".$v->goods_id)}}'><img src='{{asset("Public/upload/goods/thumb/{$v->goods_id}/goods_thumb_{$v->goods_id}_180_180.jpeg")}}' style="width:180px; height:180px"/></a>
                                </div>
                                <div class="best_name">
                                    <a href='{{url("home/goods/goodsinfo/".$v->goods_id)}}'>
                                        <span>{{str_limit($v->goods_name,23)}}</span>
                                    </a>
                                </div>
                                <div class="best_Introduction">
                                    <div class="intr-t">{{str_limit($v->goods_remark,46)}}</div>
                                    <div class="intr-b">买的更多更优惠</div>
                                </div>
                                <div class="price">
                                    <span>¥</span><em>{{$v->shop_price}}</em>
                                </div>
                                <div class="imm-button">
                                    <a href='{{url("home/goods/goodsinfo/".$v->goods_id)}}'><span>立即抢购</span></a>
                                </div>
                                <div class="tag">
                                    <if condition="$v['is_new'] eq '1'">
                                        <img src="{{asset('Static/images/1382542488099.png')}}" alt="热卖" />
                                        <else/>
                                        <img src="{{asset('Static/images/1382593860805.png')}}" alt="热卖" />
                                    </if>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!--热卖结束-->

    <div class="bs-ri">
        <div class="ris-adve"><!--公告上方广告位-->
            @foreach($topAd as $v)
                <a href="{{$v->ad_link}}" @if($v->target ==1) >target="_blank" @endif >
                    <img src="{{asset($v->ad_code)}}" title="" style=""/>
                </a>
            @endforeach
        </div>
        <div class="ris-notice">
            <div class="notice_t">
                <ul>
                    <li id="not_col"><a href="">公告</a></li>
                    <li id="not_new"><a href="">新闻</a></li>
                </ul>
            </div>
            <div class="notice_b">
                <ul class="nob1">
                    @foreach($notice as $v)
                        <li>
                            <a href="{{url('home/article/detail/'.$v->article_id)}}">{{str_limit($v->title,25)}}</a>
                        </li>
                    @endforeach
                </ul>
                <ul class="nob2">
                    @foreach($news as $v)
                        <li><a href="{{url('home/article/detail/'.$v->article_id)}}">{{str_limit($v->title,33)}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!--公告下方广告位-->
        <div class="ris-as">
            @foreach($underAd as $v)
                <p><a href="{{$v->ad_link}}" @if($v->target ==1) >target="_blank" @endif ><img src="{{asset($v->ad_code)}}" width="278" height="132" title="" style=""></a></p>
            @endforeach
        </div>
    </div>
</div>

</div>

<!--------热卖-首发-新闻-公告-结束-------------->


<!--------中部banner-开始-------------->
<div class="layout">
    <div class="cen-banne">
        @foreach($middleAd as $v)
            <a href="{{$v->ad_link}}" @if($v->target == 1)>target="_blank" @endif >
                <img src="{{asset($v->ad_code)}}" width="1200" height="160"  title="" style=""/>
            </a>
        @endforeach
    </div>
</div>
<!--------中部banner-结束-------------->

<!--------楼层-开始-------------->
{{--{{dd($parent)}}--}}
<div class="layout layer" id="parent">
    <script>
        var flag = true;
        $(document).scroll(function(){
            $a = $(this).scrollTop();
            if(40<$a){
                if(flag){
                    $.get("hot",function(data){
                        $("#parent").html("");
                        $("#parent").append(data);
                    });
                    flag = false;
                }
            }
        });
    </script>
</div>
<!--------楼层-结束-------------->


<!--------底部banner-开始-------------->
<div class="layout layer">
    <div class="cen-banne">
        @foreach($footAd as $v)
            <a href="{{$v->ad_link}}" @if($v->target == 1)>target="_blank" @endif >
                <img src="{{url($v->ad_code)}}" width="1200" height="160" title="" style=""/>
            </a>
        @endforeach
    </div>
</div>

<!--------底部banner-结束-------------->

<!--------footer-开始-------------->
<!--------   <include file="Public/footer" /> -------------->
@include("Home/Public/footer")

<!--------footer-结束-------------->
<script src="{{URL::asset('js/layui/layui.js')}}"></script>
<script>
    $(document).ready(function(){
        /* 新闻和公告的 js 切换*/
        $(".nob2").css("display", "none");
        $("#not_col").hover(function(){
            $(".nob1").css("display", "block");
            $(".nob2").css("display", "none");
            $(this).css('background-color','#FFF');
            $("#not_new").css('background-color','#fcf7f7');
        });
        /* 新闻和公告的 js 切换*/
        $("#not_new").hover(function(){
            $(".nob2").css("display", "block")
            $(".nob1").css("display", "none");
            $(this).css('background-color','#FFF');
            $("#not_col").css('background-color','#fcf7f7');
        })

        layui.use('flow', function(){
            var flow = layui.flow;
            //按屏加载图片
            flow.lazyimg({
                elem: '#LAY_demo3 img',
//                scrollElem: '#LAY_demo3' //一般不用设置，此处只是演示需要。
            });
        });
    });
</script>

<script src="{{asset('js/jqueryUrlGet.js')}}"></script><!--获取get参数插件-->
<script>
    set_first_leader(); //设置推荐人
    // 如果是手机浏览器跳到手机
    if(isMobileBrowser())
        location.href="{:U('Mobile/Index/index')}";
</script>

</body>
</html>