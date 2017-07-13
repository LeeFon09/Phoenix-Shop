@extends('layouts.home')

@section('title','商品列表')

@section('content')
<link rel="stylesheet" href="{{asset('Static/css/page.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('Static/css/category.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('Static/css/common.min.css')}}" type="text/css">

{{--<script  src="{{asset('Static/js/jquery.jqzoom.js')}}"></script>--}}
<script type="text/javascript" src="{{asset('js/pc_common.js')}}"></script>
<script type="text/javascript" src="{{asset('js/layer/layer.js')}}"></script><!--弹窗js 参考文档 http://layer.layui.com/-->
<div id="warpper" class="clearfix">
	<!-- 导航栏开始 -->
	<div id="path-new" class="clearfix colaaa text13">
        @if($currCate->level == 1)
            <div class="fl">
                <div class="u-nav-attr">
                    <a href="{{url('home/goods/goodslist',['id'=>$currCate->id])}}">{{$currCate -> top_name}}</a>
                </div>
            </div>
            <div class="u-left-icon"><i class="z-arrow"></i></div>
        @elseif($currCate->level == 2)
            <div class="fl">
                <div class="u-nav-attr">
                    <a href="{{url('home/goods/goodslist',['id'=>$currCate->parent_id])}}">{{$currCate -> top_name}}</a>
                </div>
            </div>
            <div class="u-left-icon"><i class="z-arrow"></i></div>
            <div class="fl">
                <div class="u-nav-attr">
                    <a href="{{url('home/goods/goodslist',['id'=>$currCate->id])}}">{{$currCate -> name}}</a>
                </div>
            </div>
            <div class="u-left-icon"><i class="z-arrow"></i></div>
        @else
            <div class="fl">
                <div class="u-nav-attr">
                    <a href="{{url('home/goods/goodslist',['id'=>$currCate->top_id])}}">{{$currCate -> top_name}}</a>
                </div>
            </div>
            <div class="u-left-icon"><i class="z-arrow"></i></div>
            <div class="fl">
                <div class="u-nav-attr">
                    <a href="{{url('home/goods/goodslist',['id'=>$currCate->parent_id])}}">{{$currCate -> parent_name}}</a>
                </div>
            </div>
            <div class="u-left-icon"><i class="z-arrow"></i></div>
            <div class="fl">
                <div class="u-nav-attr">
                    <a href="{{url('home/goods/goodslist',['id'=>$currCate->id])}}">{{$currCate -> name}}</a>
                </div>
            </div>
            <div class="u-left-icon"><i class="z-arrow"></i></div>
        @endif
        <foreach name="filter_menu" item="v" key="k">
			<a title="{$v->text}" href="{$v->href}" class="u-av-label">{$v->text}<i></i></a>
		</foreach>

	</div>
	<!-- 导航栏结束 -->
<!-- 左侧分类列表 -->
	<div class="cata_cart_left">
		<div class="m-cart">
			<h2 class="title">{{$currCate -> top_name}}</h2>
			<div id="cata_list">
				<!-- 分类树　>>　开始 -->
				<ul class="menu_3">
				@foreach($cateArr as $k => $obj)
					<li class="menu_two">
				<!-- 逻辑说明：如果当前分类为一级分类子面板不展开，否则则展开子面板 -->
					@if($obj->id == $currCate->open_id)
						<span name="submenuicon" class="menuminus"></span>
					@else
						<span name="submenuicon" class="menuplus"></span>
					@endif
						<a  href="{{url('home/goods/goodslist',['id'=>$obj->id])}}" class="menu3_title">{{$obj -> name}}</a>
						<div class="second_div">
							<span class="second_span"></span>
							<ul @if($obj->id == $currCate->open_id)
							        style="display: block;"
							    @else
							        style="display: none;"
								@endif>
							@foreach($obj->second_menu as $secobj)
								<li style="height: 37px;">
									<a  href="{{url('home/goods/goodslist',['id'=>$secobj->id])}}"
							        @if($secobj->id == $currCate->select_id) class="selected" @endif>{{$secobj -> name}}
									</a>
								</li>
							@endforeach
							</ul>
						</div>
					</li>
				@endforeach
				</ul>
				<!-- 分类树　>>　结束 -->
			</div>
		</div>

		<!-- 推荐热卖-->
		<div class="m-rehotbox mt10" style='display:block;'>
			<h2 class="rehottit">推荐热卖</h2>
			<div class="rehotcon" id="rehotcon">
			@foreach($recGoods as $obj)
				<div class="hotitem">
					<div class="itemin">
						<a class="proname"  href="{{url('home/goods/goodsinfo',['id'=>$obj->goods_id ])}}">
							<img src="{{$obj->original_img}}" style="width:200px;height: 200px;">
							<span>{{$obj->goods_name}}</span>
						</a>
						<p class="itemprice">
							<span class="new" rehot="90101748934_201480" style="display: block; float: left;">
								<i class="fn-rmb">¥</i>{{$obj->market_price}}</span>
							<span class="old"><i class="fn-rmb">¥</i>{{$obj->shop_price}}</span>
						</p>
					</div>
				</div>
			@endforeach
			</div>
		</div>
	</div>
	<!-- 右侧商品规格列表 -->
	<div class="cata_shop_right" id="tracker_category">
		<!-- 館頁屬性 -->
		<div class="attribute_content">
			<div class="attrs">
				<!--筛选品牌-->
				<if condition="$filter_brand neq null">
					<div class='m-tr' >
						<div class='g-left'>
							<p>品牌</p>
						</div>
						<div class='g-right ' >
							<div class="g-list">
								<ul class="f-list h76 ">
                                <foreach name="filter_brand" item="v" key="k">
                                    <li> <a data-href="" href="{$v[href]}" data-key='brand' data-val='{$v[id]}'><i></i>{$v[name]}</a> </li>
                                </foreach>
								</ul>
								<div class="f-ext">
									<a  class="f-more brandMore" data-attr="attr_600002" href="javascript:;">更多<i></i></a>
									<a  class="f-check brand" data-attr="attr_600002" href="javascript:;"><i></i>多选</a>
								</div>
								<div class="g-btns">
									<a  href="javascript:;" class="u-confirm" onClick="submitMoreFilter('brand',this);">确定</a>
									<a href="javascript:;" class="u-cancel brand">取消</a>
								</div>
							</div>
						</div>
					</div>
				</if>
				<!--筛选规格-->
				<foreach name="filter_spec" item="v" key="k">
					<div class='m-tr' >
						<div class='g-left'>
							<p>{$v.name}</p>
						</div>
						<div class='g-right ' >
							<div class="g-list">
								<ul class="f-list ">
									<foreach name="v[item]" item="v2" key="k2">
										<li> <a id='' data-href="" href="{$v2[href]}" data-key='{$v2[key]}' data-val='{$v2[val]}'><i></i>{$v2[item]}</a> </li>
									</foreach>
								</ul>
								<div class="f-ext">
									<a  class="f-more " data-attr="attr_601115" href="javascript:;">更多<i></i></a>
									<a  class="f-check " data-attr="attr_601115" href="javascript:;"><i></i>多选</a>
								</div>
								<div class="g-btns">
									<a  href="javascript:;" class="u-confirm" onClick="submitMoreFilter('spec',this);">确定</a>
									<a href="javascript:;" class="u-cancel ">取消</a>
								</div>
							</div>
						</div>
					</div>
				</foreach>
				<!--筛选属性-->
				<foreach name="filter_attr" item="v" key="k">
					<div class='m-tr' >
						<div class='g-left'>
							<p>{$v.attr_name}</p>
						</div>
						<div class='g-right ' >
							<div class="g-list">
								<ul class="f-list h76 ">
									<foreach name="v[attr_value]" item="v2" key="k2">
										<li> <a data-href="{$v2[href]}" href="{$v2[href]}" data-key='{$v2[key]}' data-val='{$v2[val]}'><i></i>{$v2[attr_value]}</a> </li>
									</foreach>
								</ul>
								<div class="f-ext">
									<a  class="f-more brandMore" data-attr="attr_600002" href="javascript:;">更多<i></i></a>
									<a  class="f-check brand" data-attr="attr_600002" href="javascript:;"><i></i>多选</a>
								</div>
								<div class="g-btns">
									<a  href="javascript:;" class="u-confirm" onClick="submitMoreFilter('attr',this);">确定</a>
									<a href="javascript:;" class="u-cancel brand">取消</a>
								</div>
							</div>
						</div>
					</div>
				</foreach>
				<!--价格筛选-->
				<if condition="$filter_price neq null">
					<div class='m-tr' >
						<div class='g-left'>
							<p>价格</p>
						</div>
						<div class='g-right ' >
							<div class="g-list">
								<ul class="f-list h76 ">
									<foreach name="filter_price" item="v" key="k">
										<li> <a href="{$v[href]}" data-attr-desc=''><i></i>{$v[value]}</a> </li>
									</foreach>
									<li class="m-pricebox">
										{{--
										<form action="<php echo urldecode(url("home/goods/goodslist",$filter_param,''));?>" method="post" id="price_form">
											<input type="text" class="u-pri-start" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" name="start_price" id="start_price" />
											-
											<input type="text" class="u-pri-end" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')"  name="end_price" id="end_price" />
											<span style="cursor:pointer;" class="z-btn ensure03 u-btn-pri" href="javascript:;" onClick="if($('#start_price').val() !='' && $('#end_price').val() !='' ) $('#price_form').submit();">确认</span>
										</form>
										--}}
									</li>
								</ul>
							</div>
						</div>
					</div>
				</if>
				<!--价格筛选 end-->
				<a  class="f-out-more" href="javascript:;">更多选项<i></i></a> </div>
		</div>
		<!-- 館頁屬性 -->

		<!-- 商品列表 -->
		<div class="searchbox">
			<div class="list clearfix">
				<ul class="left text12" id="order_ul">

				</ul>
				<!-- Page -->
				<div class="right text12" id="pagenavi">
					<div class="all-number">
						<span>共{{$pageObj->totalRows}}个商品</span>
					</div>
					<p class="pageArea" data-countPage="1">
						<!--<a class="bg_img1"></a>-->
						<span class="colf22e01 fontT">{{$pageObj->nowPage}}</span>
						/
						<span class="page_count fontT">{{$pageObj->totalPages}}</span>
						<!--<a href="" class="bg_img2"></a> </p>-->
				</div>
				<!-- Page End-->
			</div>
			<!-- list -->
		</div>
		<!-- searchbox -->

		<ul id="cata_choose_product" class="cart_containt clearfix listContainer">
			@foreach($goodsList as $obj)
			<li>
				<div class="nosinglemore"></div>
				<div class="listbox clearfix">
					<!-- 圖片 -->
					<div class="listPic">
						<a rel="nofollow" target="_blank" href="{{url('home/goods/goodsinfo',array('id'=>$obj->goods_id))}}">
							<img style="width: 200px;height: 200px;" src="{{$obj->original_img}}" class="fn_img_lazy">
						</a>
					</div>
					<div class="list-scroll J_scrollBox clearfix"> <a href="javascript:void(0);" class="list-scroll-left cbtn"></a>
						<div class="list-scroll-warp J_hideBox">
							<dl class="J_mBox clearfix">
							@unless(empty($obj->small_images))
								@foreach($obj->small_images as $sobj)
								<dd>
									<a href="javascript:void(0);">
										<img data-img="{{$sobj->image_url}}" src="{{$sobj->image_url}}" style="width: 30px;height: 30px;">
									</a>
								</dd>
								@endforeach
							@endunless
							</dl>
						</div>
						<a href="javascript:void(0);" class="list-scroll-right cbtn"></a>
					</div>

					<!-- 价格 -->
					<div class="discountPrice">
						<div class="price-cash">
							<span class="text13 colf22e01">¥</span>
							<span class="text18 colf22e01 fontT">{{$obj->shop_price}}</span>
							<del></del>
							<span class="text12" style="float: right;color: gray;position: relative;top:3px">{{$obj->sales_sum}}人付款</span>
						</div>
					</div>
					<!-- 价格 end-->
					<!-- 品名 -->
					<div class="listDescript">、
						<a target="_blank" href="{{url('home/goods/goodsinfo',array('id'=>$obj->goods_id))}}" class="text13">{{$obj->goods_name}}</a>
					</div>
					<!-- 價格及字樣 -->
					<div class="itemPrice" >
						<div class="cart_wrapper" >
							{{--<a style="cursor:pointer;" class="cartlimit"  onclick="AjaxAddCart('{{$obj->goods_id}}',1,0)" ></a>--}}
                            <button class="add-to-collection"  onclick="AjaxAddToCart('{{url('home/goods/goodsinfo',array('id'=>$obj->goods_id))}}')" >立即购买</button>
                            <button class="add-to-collection"  onclick="AjaxAddToCollection('{{url("home/goods/goodsCollect/".$obj->goods_id)}}')" >加入收藏</button>
						</div>
					</div>
				</div>
			</li>
			@endforeach
		</ul>
		{!! $pageObj->show() !!}

	</div>

</div><!-- wrap结束 -->
@stop


@section('javascript')
<script type="text/javascript">
	//############   分类导航折叠        ############
	$('span[name="submenuicon"]').each(function () {
		$(this).click(function () {
			if ($(this).hasClass('menuplus')) {
				$(this).removeClass('menuplus').addClass('menuminus');
				$(this).siblings('.second_div').find('ul').show();
			} else {
				$(this).removeClass('menuminus').addClass('menuplus');
				$(this).siblings('.second_div').find('ul').hide();
			}
		});
	})

	//############   更多类别属性筛选       ###########
	$('.f-out-more').click(function () {
		$('.m-tr').each(function (i, o) {
			if (i > 7) {
				var attrdisplay = $(o).css('display');
				if (attrdisplay == 'none') {
					$(o).css('display', 'block');
				}
				if (attrdisplay == 'block') {
					$(o).css('display', 'none');
				}
			}
		});
		if ($(this).hasClass('checked')) {
			$(this).removeClass('checked tex-center').html('收起<i class="checked"></i>');
		} else {
			$(this).addClass('checked tex-center').html('更多选项');
		}
	})
	$('.f-out-more').trigger('click').html('更多选项'); //  默认点击一下

	//############   更多条件选择        ###########
	$('.f-more').click(function () {
		if ($(this).hasClass('checked')) {
			$(this).parent().siblings('ul').removeClass('z-show-more');
			$(this).removeClass('checked').html('更多<i></i>');
		} else {
			$(this).parent().siblings('ul').addClass('z-show-more');
			$(this).addClass('checked').html('收起<i class="checked"></i>');
		}
	})

	var cancelBtn = null;
	//############   多选框        ###########
	$('.f-check').click(function () {
		var _this = this;
		var st = 0;
		//关闭前一个多选框
		if (cancelBtn != null) {
			$(cancelBtn).parent().siblings('ul').removeClass('z-show-more');
			$(cancelBtn).parent().siblings('ul').find('li >a').each(function (i, o) {
				$(o).removeClass('select selected');
				$(o).attr('href', $(o).data('href'));
				$(o).children('i').removeClass('selected').css('display', '');
				$(o).unbind('click');
			});
			$(cancelBtn).parent().siblings('.f-ext').show().children('a').removeClass('checked');
			$(cancelBtn).parent().hide();
			$(cancelBtn).siblings().removeClass('u-confirm01');
		}
		cancelBtn = $(_this).parent().siblings('div').find('.u-cancel');

		//打开多选框
		$(_this).addClass('checked');
		$(_this).siblings().addClass('checked');
		$(_this).parent().siblings('.g-btns').show();
		$(_this).parent().siblings('ul').addClass('z-show-more');
		$(_this).parent().siblings('ul').find('li>a').each(function (i, o) {
			$(o).addClass('select');
			$(o).children('i').css('display', 'inline');
			$(o).attr('href', 'javascript:;');
			$(o).bind('click', function () {
				if ($(o).hasClass('selected')) {
					$(o).removeClass('selected');
					$(o).children('i').removeClass('selected');
					st--;
				} else {
					$(o).addClass('selected');
					$(o).children('i').addClass('selected');
					$(_this).parent().siblings('.g-btns').children('.u-confirm').addClass('u-confirm01');
					st++;
				}
				//如果没有选中项,确定按钮点不了
				if (st == 0) {
					$(_this).parent().siblings('.g-btns').children('.u-confirm').removeClass('u-confirm01');
				}
			});
		});
		$(_this).parent().hide();
	})


	//############   取消多选        ###########
	$('.g-btns .u-cancel').each(function () {
		$(this).click(function () {
			$(this).parent().siblings('ul').removeClass('z-show-more');
			$(this).parent().siblings('ul').find('li >a').each(function (i, o) {
				$(o).removeClass('select selected');
				$(o).attr('href', $(o).data('href'));
				$(o).children('i').removeClass('selected').css('display', '');
				$(o).unbind('click');
			});
			$(this).parent().siblings('.f-ext').show().children('a').removeClass('checked');
			$(this).parent().hide();
			$(this).siblings().removeClass('u-confirm01');
		});
	})

	//############   产品图片切换        ###########

	$('.list-scroll-warp').each(function () {
		var _this = this;

		$(_this).children().children().each(function (i, o) {
			$(o).mouseover(function () {
				$(o).siblings().removeClass('cur');
				$(o).addClass('cur');
				var img_src = $(o).children().children().attr('data-img');
				$(_this).parent().siblings('.listPic').find('a').children().attr('src', img_src);
			})
		})
	})

	//############   点击多选确定按钮      ############
	// t 为类型  是品牌 还是 规格 还是 属性
	// btn 是点击的确定按钮用于找位置
	get_parment = '';// echo json_encode($_GET); ?>;
	function submitMoreFilter(t, btn) {
		// 没有被勾选的时候
		if (!$(btn).hasClass("u-confirm01"))
			return false;

		// 获取现有的get参数
		var key = ''; // 请求的 参数名称
		var val = new Array(); // 请求的参数值
		$(btn).parent().siblings(".f-list").find("li > a.selected").each(function () {
			key = $(this).data('key');
			val.push($(this).data('val'));
		});
		//parment = key+'_'+val.join('_');

		// 品牌
		if (t == 'brand') {
			get_parment.brand_id = val.join('_');
		}
		// 规格
		if (t == 'spec') {
			if (get_parment.hasOwnProperty('spec')) {
				get_parment.spec += '@' + key + '_' + val.join('_');
			}
			else {
				get_parment.spec = key + '_' + val.join('_');
			}
		}
		// 属性
		if (t == 'attr') {
			if (get_parment.hasOwnProperty('attr')) {
				get_parment.attr += '@' + key + '_' + val.join('_');
			}
			else {
				get_parment.attr = key + '_' + val.join('_');
			}
		}
		// 组装请求的url
		var url = '';
		for (var k in get_parment) {
			url += "&" + k + '=' + get_parment[k];
		}

//    console.log('get_parment',get_parment);
		location.href = "/index.php?m=Home&c=Goods&a=goodslist" + url;
	}
</script>
@stop
