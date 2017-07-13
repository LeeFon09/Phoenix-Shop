<link rel="stylesheet" href="{{asset('Static/css/index.css')}}" type="text/css">
<div class="site-topbar">
    <div class="layout">
        <div class="t1-l">
            <ul class="t1-l-ul">
                <li class="t1font"><a target="_blank" href="{{url('/Home/Article/detail',array('article_id'=>22))}}">在线客服</a></li>
                <li class="t1img">&nbsp;</li>

                <li class="t1font"><a  href="{{url('/')}}">凤凰涅槃</a></li>

                <li class="t1img">&nbsp;</li>
            </ul>
        </div>
        <div class="t1-r">
            <ul class="t1-r-ul" style='{{session("user") !== null?"display:block":"display:none"}}'><!--sytle中有display:none;-->
                {{--{{dd(session("user"))}}--}}
                <li class="t1font"> <a href="{{url('home/user/index')}}" class="logon userinfo">
                        {{ $nickname }}</a></li>
                <li class="t1img"><a href="{{url('home/user/logout')}}">安全退出</a></li>
                <li class="t1img">&nbsp;</li>
                <li class="t1font"><a href="{{url('home/order/orderList')}}">我的订单</a></li>
                <li class="t1img">&nbsp;</li>
                <li class="t1font"><a href="{{url('home/cart/cart')}}">购物车</a></li>
                <li class="t1img">&nbsp;</li>
                <li class="t1font"><a href="#">网站地图</a></li>
                <li class="t1img">&nbsp;</li>                
            </ul>
            <ul class="t1-r-ul" style='{{session("user") !== null?"display:none":"display:block"}}'><!--sytle中有display:none;-->
                <li class="t1font"><a href="{{url('login')}}">登录</a></li>
                <li class="t1img">&nbsp;</li>
                <li class="t1font"><a href="{{url('home/cart/cart')}}">购物车</a></li>
                <li class="t1img">&nbsp;</li>
                <li class="t1font"><a href="#">网站地图</a></li>
                <li class="t1img">&nbsp;</li> 
            </ul>
        </div>
    </div>
</div>