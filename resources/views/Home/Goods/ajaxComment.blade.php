
@foreach($comment as $v)
    <div class="pro-comment-item po-re pa-bo-20 di-ta">
        <div class="pro-comment-user fl te-al pa-0-15 wi90 po-re">
            <p class="procomment-img">
                <!-- 没有头像就用默认头像 -->
                <img alt="" src="{{asset('Static/images/defaultface_user_small.png')}}" />
            </p>
            <p class="procomment-name">{{$v->username}}</p>
            <s class="procomment-tag">
              {{--<!--  <i></i>-->  a:3:{i:0;s:49:"/Public/upload/goods/2016/01-21/56a092d85d0df.jpg";i:1;s:49:"/Public/upload/goods/2016/01-21/56a092d8406fc.jpg";i:2;s:49:"/Public/upload/goods/2016/01-21/56a092d81fe22.jpg";}--}}
            </s>
        </div>
        <div class="pro-user-comment-main ma-0-25-0-0 ov-hi">
            <div class="pro-user-comment">
                <div class="comm-t1 ov-hi">
                    <div class="pro-user-score fl">
                        <em>发货速度</em>  
                        <span class="starRating-area">
                          <s style="width:{{($v->deliver_rank)/5*100}}%"></s>
                        </span>
                        <em>客服态度</em>  
                        <span class="starRating-area">
                          <s style="width:{{($v->service_rank)/5*100}}%"></s>
                        </span>
                        <em>商品质量</em>  
                        <span class="starRating-area">
                          <s style="width:{{($v->goods_rank)/5*100}}%"></s>
                        </span>      
                    </div>                
                    <div class="pro-user-impre fl ov-hi">
                    <!--
                        <ul>
                            <li>不错</li>
                            <li>大屏幕</li>
                            <li>流畅</li>
                        </ul>
                      -->
                    </div>
                    <div class="pro-user-time wh-sp">{{date("Y-m-d",$v->add_time)}}</div>
                </div>
                <div class="comm-t2">
                    {{htmlspecialchars_decode($v->content)}}
                    <!--htmlspecialchars_decode   html_entity_decode-->
                    <br/>            
                    <!--晒单-->
                    @if(unserialize($v->img))
                    @foreach(unserialize($v->img) as $val)
	                    <a href="{{url($val)}}" target="_blank"><img alt="" src="{{url($val)}}" width="120" height="120" /></a>&nbsp;&nbsp;
                    @endforeach
                    @endif
                    <!--商家回复-->
                    @foreach($reply as $val)
                       @if(isset($val->parent_id) && $val->parent_id == $v->comment_id)
	                    <p class="salesperson"><span>商家回复：</span>{{$val->content}}</p>
                       @endif
                    @endforeach
                </div>
            </div>
            <div class="arrow"></div>
        </div>
    </div>
@endforeach

<div class="eval-cen-ri fr pa-to-17 pa-ri-25 te-al-ri di-bl">{$page}</div>
<script>
    // 点击分页触发的事件
    $("#ajax_comment_return .pagination  a").click(function(){
        cur_page = $(this).data('p');
        ajaxComment(commentType,cur_page);
    });
</script>