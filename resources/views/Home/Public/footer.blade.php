<div class="footer">
    <div class="layout quality layer">
        <ul class="footer_quality">
            <li><i></i>品质保证</li>
            <li  class="f2"><i></i>7天退换 15天换货</li>
            <li  class="f3"><i></i>100元起免运费</li>
            <li  class="f4"><i></i>448家维修网点 全国联保</li>
        </ul>
    </div>
    <div class="helpful layout">
    {{--<tpshop sql="select * from `__PREFIX__article_cat` where cat_id in(1,2,3,4,7)" key="k" item='v'>--}}
    @foreach($artIds as $artId => $artName)
        {{--<dl <if condition="$k neq 0"></if> >--}}
        <dl >
            <dt>{{$artName}}</dt>
            <dd>
                <ol>
                    {{--<tpshop sql="select * from `__PREFIX__article` where cat_id = $v[cat_id] and is_open=1" key="k2" item='v2'>--}}
                @foreach($subIds as $obj)
                    @if($artId == $obj->cat_id)
                    <li><a href="{{url('home/article/detail',array('article_id'=>$obj->article_id))}}" target="_blank">{{$obj->title}}</a></li>
                    @endif
                @endforeach
                    {{--</tpshop>--}}
                </ol>
            </dd>
        </dl>
    @endforeach
 	{{--</tpshop>--}}
     </div>
     <div class="keep-on-record">
        <p>
        Copyright © 2016-2025 {$tpshop_config['shop_info_store_name']}  版权所有 保留一切权利 备案号:{$tpshop_config['shop_info_record_no']}
        
        <!--您好,请您给TPshop留个友情链接-->
        &nbsp;&nbsp;<a href="http://www.tp-shop.cn/">TPshop开源商城</a>
        <!--您好,请您给TPshop留个友情链接-->
        </p>
     </div>
 </div>
 
