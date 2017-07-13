

        @foreach($parent as $k=>$val)
        <div class="ground-flool">
            <div class="flool-t">
                <h2><a href='{{url("Home/Goods/id/".$val->id)}}'>{{$val->name}}</a></h2>
                @foreach($child[$k] as $val2)
                    <em>{{$val2->name}}</em>
                @endforeach
                <ul><!--
					<li><a href="{:U('/Home/Goods/goodsList',array('brand_id'=>1))}">诺基亚</a></li>
                    -->

                </ul>
            </div>
{{--            {{dd($list)}}--}}
            <!--大块热卖商品开始-->
            <div class="flool-b layer" id="LAY_demo3">
                <ul class="flool-cle">
                    @foreach($list as $key=>$v)
                        @if($key == $k)
                            @foreach($v as $vv)
                                <li class="sellers sell_ons">
                                    <div class="best">
                                        <div class="best-about">
                                            <div class="best_img best_img2">
{{--                                                <a href='{{url("home/goods/goodsinfo/".$vv[0]->goods_id)}}'><img lay-src='{{url("/Public/upload/goods/thumb/{$vv[0]->goods_id}/goods_thumb_{$vv[0]->goods_id}_222_222.jpeg")}}' /></a>--}}
                                                <a href='{{url("home/goods/goodsinfo/".$vv[0]->goods_id)}}'><img lay-src='{{url($vv[0]->original_img)}}' /></a>
                                            </div>
                                            <div class="best_name">
                                                <a href='{{url("home/goods/goodsinfo/".$vv[0]->goods_id)}}'><span>{{str_limit($vv[0]->goods_name,22)}}</span></a>
                                            </div>
                                            <div class="best_Introduction">
                                                <div class="intr-t">{{$vv[0]->keywords}}</div>
                                                <div class="intr-b">{{str_limit($vv[0]->goods_remark, 75)}}</div>
                                            </div>
                                            <div class="price">
                                                <span>¥</span><em>{{$vv[0]->shop_price}}</em>
                                            </div>
                                            <div class="imm-button">
                                                <a href='{{url("home/goods/goodsinfo/".$vv[0]->goods_id)}}'><span>立即抢购</span></a>
                                            </div>
                                            <div class="tag">
                                                <img lay-src="{{asset('Static/images/1382542488099.png')}}" alt="热卖" />
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                <!--大块热卖商品结束-->
                    <!--六个小块商品开始-->
                    @foreach($lists as $a=>$b)
                        @if($a == $k)
                            @foreach($b as $c)
                                @foreach($c as $d)
                                    <li class="sellers sellers2 sell_ons">
                                        <div class="best">
                                            <div class="best-about">
                                                <div class="best_img best_img2 best_img3">
                                                    {{--<a href='{{url("home/goods/goodsinfo/".$d->goods_id)}}'><img lay-src='{{str_finish(url("/Public/upload/goods/thumb/{$d->goods_id}/goods_thumb_{$d->goods_id}_222_222."),"jpeg")}}' /></a>--}}
                                                    <a href='{{url("home/goods/goodsinfo/".$d->goods_id)}}'><img lay-src='{{url($d->original_img)}}' /></a>
                                                </div>
                                                <div class="intr-t intr-t3">{{str_limit($d->goods_name,36)}}</div>
                                                <div class="price prices">
                                                    <span>¥</span><em>{{str_limit($d->shop_price)}}</em>
                                                </div>
                                                <div class="tag">

                                                    <!--<img src="__STATIC__/images/1382593860805.png" alt="首发" />-->
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                            @endforeach
                        @endforeach
                    @endif
                @endforeach
                <!--六个小块商品结束-->
                </ul>
            </div>
        </div>
        @endforeach





