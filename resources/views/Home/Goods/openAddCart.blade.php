<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>加入购物车-{$tpshop_config['shop_info_store_title']}</title>
<meta http-equiv="keywords" content="{$tpshop_config['shop_info_store_keyword']}" />
<meta name="description" content="{$tpshop_config['shop_info_store_desc']}" />
</head>
<body>
<link rel="stylesheet" type="text/css" href="{{url('Static/css/detail.css')}}">
<div id="shopdilog">
  <div class="ui-popup ui-popup-modal ui-popup-show ui-popup-focus">
    <div i="dialog" class="ui-dialog">
      <div class="ui-dialog-arrow-a"></div>
      <div class="ui-dialog-arrow-b"></div>
      <table class="ui-dialog-grid">
        <tbody>
          <tr>
            <td i="body" class="ui-dialog-body">
              <div i="content" class="ui-dialog-content" id="content:1459321729418" style="width: 450px; height: auto;">
                <div id="addCartBox" class="collect-public" style="display: block;">
                  <div class="colect-top"> <i class="colect-icon"></i> 
                    <!--<i class="colect-fail"></i>-->
                    <div class="conect-title">
                      <span>添加成功</span>
                      <div class="add-cart-btn fn-clear"> 
                          <a href="javascript:;" onclick="javascript:parent.layer.closeAll('iframe');" class="ui-button ui-button-f80 fl go-shopping">继续购物</a> 
                          <a href="{{url('home/cart/cart')}}" target="_parent" class="ui-button ui-button-122 fl">去购物车结算</a>
                      </div>
                    </div>
                  </div>
                  <div id="watch">
                    <span>热卖产品：</span>
                    <ul class="fn-clear buy-list">
                    @foreach($hot as $v)
                      <li>
                        <a href="{{url('home/goods/goodsinfo/'.$v->goods_id)}}" class="watch-img" target="_parent"><img src='{{url("/Public/upload/goods/thumb/{$v->goods_id}/goods_thumb_{$v->goods_id}_200_200.jpeg")}}' /></a>
                        <h4><a href="{{url('home/goods/goodsinfo/'.$v->goods_id)}}" target="_parent">{{$v->goods_name}}</a></h4>
                        <p><q class="fn-rmb">¥</q><strong class="fn-rmb-num">{{$v->shop_price}}</strong></p>
                      </li>
                    @endforeach
                    </ul>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr>            
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>