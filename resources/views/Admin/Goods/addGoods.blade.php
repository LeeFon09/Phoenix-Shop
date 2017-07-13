@include('Admin/Public/min-header')
<!--物流配置 css -start-->
<style>
    ul.group-list {
        width: 96%;
        min-width: 1000px;
        margin: auto 5px;
        list-style: disc outside none;
    }

    ul.group-list li {
        white-space: nowrap;
        float: left;
        width: 150px;
        height: 25px;
        padding: 3px 5px;
        list-style-type: none;
        list-style-position: outside;
        border: 0px;
        margin: 0px;
    }
</style><!--物流配置 css -end-->


<div class="wrapper">
    <include file="Public/breadcrumb"/>
    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">
                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
                <a href="javascript:;" class="btn btn-default" data-url="http://www.tp-shop.cn/Doc/Index/article/id/1007/developer/user.html" onclick="get_help(this)"><i class="fa fa-question-circle"></i> 帮助</a>
            </div>
            <div class="panel panel-default">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif

                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i>商品详情</h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">通用信息</a></li>
                        <li><a href="#tab_goods_images" data-toggle="tab">商品相册</a></li>
                        <li><a href="#tab_goods_spec" data-toggle="tab">商品规格</a></li>
                        <li><a href="#tab_goods_attr" data-toggle="tab">商品属性</a></li>
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="addEditGoodsForm" enctype="multipart/form-data" action="{{ url('admin/Goods/goodsList') }}">
                    {{ csrf_field() }}
                    <!--通用信息-->
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_tongyong">

                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td>商品名称:</td>
                                        <td>
                                            <input type="text" value="{{ old('goods_name') }}" name="goods_name" class="form-control" style="width:550px;"/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>商品分类:</td>
                                        <td>
                                            <div class="col-xs-3">
                                                <select name="cat_id" id="cat_id" class="form-control" style="width:250px;margin-left:-15px;">
                                                    <option value="">请选择商品分类</option>
                                                @foreach($type as $t)
                                                    <option value="{{ $t->id }}">
                                                        {{ $t->name }}
                                                    </option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xs-3">
                                                <select name="cat_id_2" id="cat_id_2" class="form-control" style="width:250px;margin-left:-15px;">
                                                    <option value="">请选择商品分类</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-3">
                                                <select name="cat_id_3" id="cat_id_3" class="form-control" style="width:250px;margin-left:-15px;">
                                                    <option value="">请选择商品分类</option>
                                                </select>
                                            </div>
                                            <span id="err_cat_id" style="color:#F00; display:none;"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>所属品牌:</td>
                                        <td>
                                            <select name="brand_id" id="brand_id" class="form-control" style="width:250px;">
                                                <option value="">所有品牌</option>
                                            @foreach($brand as $b)
                                                <option value="{{ $b->id }}">
                                                    {{ $b->name }}
                                                </option>
                                            @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>本店售价:</td>
                                        <td>
                                            <input type="text" value="{{ old('shop_price') }}" name="shop_price" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                                            <span id="err_shop_price" style="color:#F00; display:none;"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>市场价:</td>
                                        <td>
                                            <input type="text" value="{{ old('market_price') }}" name="market_price" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                                            <span id="err_market_price" style="color:#F00; display:none;"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>成本价:</td>
                                        <td>
                                            <input type="text" value="{{ old('cost_price') }}" name="cost_price" class="form-control" style="width:150px;" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                                            <span id="err_cost_price" style="color:#F00; display:none"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>上传商品图片:</td>
                                        <td>
                                            <input type="button" value="上传图片" onclick="getUploadify2(1,'','goods','call_back');"/>
                                            <input type="text" class="input-sm" name="original_img" id="original_img" value="{{ old('original_img') }}"/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>商品关键词:</td>
                                        <td>
                                            <input type="text" class="form-control" style="width:550px;" value="{{ old('keywords') }}" name="keywords"/>用空格分隔
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>商品详情描述:</td>
                                        <td width="85%">
                                            <script id="container" name="content" type="text/plain"></script>
                                            <script type="text/javascript" charset="utf-8" src="{{ asset('js/ueditor/ueditor.config.js') }}"></script>
                                            <script type="text/javascript" charset="utf-8" src="{{ asset('js/ueditor/ueditor.all.min.js') }}"></script>
                                            <script type="text/javascript" charset="utf-8" src="{{ asset('js/ueditor/lang/zh-cn/zh-cn.js') }}"></script>
                                            <script type="text/javascript">
                                                var ue = UE.getEditor('container');
                                            </script>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--其他信息-->

                            <!-- 商品相册-->
                            <div class="tab-pane" id="tab_goods_images">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="goods_xc" style="width:100px; text-align:center; margin: 5px; display:inline-block;">
                                                <input type="hidden" name="goods_images[]" value=""/>
                                                <a href="javascript:void(0);" onclick="GetUploadify(10,'','goods','call_back2');"><img src="{{ asset('images/add-button.jpg') }}" width="100" height="100"/></a>
                                                <br/> <a href="javascript:void(0)">&nbsp;&nbsp;</a>
                                            </div>

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--商品相册-->

                            <!-- 商品规格-->
                            <div class="tab-pane" id="tab_goods_spec">
                                <table class="table table-bordered" id="goods_spec_table">
                                    <tr>
                                        <td>商品类型:</td>
                                        <td>
                                            <select name="spec_type" id="spec_type" class="form-control" style="width:250px;">
                                                <option value="0">选择商品类型</option>
                                            @foreach($goodsType as $gt)
                                                <option value="{{ $gt->id }}">{{ $gt->name }}</option>
                                            @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                </table>
                                <div id="ajax_spec_data"><!-- ajax 返回规格--></div>

                            </div>
                            <!-- 商品规格-->

                            <!-- 商品属性-->
                            <div class="tab-pane" id="tab_goods_attr">
                                <table class="table table-bordered" id="goods_attr_table">
                                    <tr>
                                        <td>商品属性:</td>
                                        <td>
                                            <select name="goods_type" id="goods_type" class="form-control" style="width:250px;">
                                                <option value="0">选择商品类型</option>
                                            @foreach($goodsType as $gt)
                                                <option value="{{ $gt->id }}">{{ $gt->name }}</option>
                                            @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- 商品属性-->
                        </div>
                        <div class="pull-right">

                            <button class="btn btn-primary" data-toggle="tooltip" type="submit" data-original-title="保存">保存</button>
                        </div>
                    </form><!--表单数据-->
                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
</div>
<script>
    $(document).ready(function () {
        $(":checkbox[cka]").click(function () {
            var $cks = $(":checkbox[ck='" + $(this).attr("cka") + "']");
            if ($(this).is(':checked')) {
                $cks.each(function () {
                    $(this).prop("checked", true);
                });
            } else {
                $cks.each(function () {
                    $(this).removeAttr('checked');
                });
            }
        });
    });

    function choosebox(o) {
        var vt = $(o).is(':checked');
        if (vt) {
            $('input[type=checkbox]').prop('checked', vt);
        } else {
            $('input[type=checkbox]').removeAttr('checked');
        }
    }
    /*
     * 以下是图片上传方法
     */
    // 上传商品图片成功回调函数
    function call_back(fileurl_tmp) {
        $("#original_img").val(fileurl_tmp);
        $("#original_img2").attr('href', fileurl_tmp);
    }

    // 上传商品相册回调函数
    function call_back2(paths) {
        var last_div = $(".goods_xc:last").prop("outerHTML");
        for (i = 0; i < paths.length; i++) {
            $(".goods_xc:eq(0)").before(last_div);	// 插入一个 新图片
            $(".goods_xc:eq(0)").find('a:eq(0)').attr('href', paths[i]).attr('onclick', '').attr('target', "_blank");// 修改他的链接地址
            $(".goods_xc:eq(0)").find('img').attr('src', paths[i]);// 修改他的图片路径
            $(".goods_xc:eq(0)").find('a:eq(1)').attr('onclick', "ClearPicArr2(this,'" + paths[i] + "')").text('删除');
            $(".goods_xc:eq(0)").find('input').val(paths[i]); // 设置隐藏域 要提交的值
        }
    }
    /**
     * 上传之后删除组图input     
     * @access   public
     * @val      string  删除的图片input
     */
    function ClearPicArr2(obj, path) {
        $.ajax({
            type: 'GET',
            url: "{{ url('admin/Goods/delupload') }}",
            data: {action: "del", filename: path},
            success: function () {
                $(obj).parent().remove(); // 删除完服务器的, 再删除 html上的图片
            }
        });
    }


    /** 以下 商品属性相关 js*/
    $(document).ready(function () {

        // 商品类型切换时 ajax 调用  返回不同的属性输入框
        $("#goods_type").change(function () {
            var goods_id = $("input[name='goods_id']").val();
            var type_id = $(this).val();
            $.ajax({
                type: 'GET',
                data: {goods_id: goods_id, type_id: type_id},
                url: "{{ url('admin/Goods/getAttrInput') }}",
                success: function (data) {
                    $("#goods_attr_table tr:gt(0)").remove()
                    $("#goods_attr_table").append(data);
                }
            });
        });
        // 触发商品类型
        $("#goods_type").trigger('change');
        $("input[name='exchange_integral']").blur(function () {
            var shop_price = parseInt($("input[name='shop_price']").val());
            var exchange_integral = parseInt($(this).val());
            if (shop_price * 100 < exchange_integral) {

            }
        });
    });


    // 属性输入框的加减事件
    function addAttr(a) {
        var attr = $(a).parent().parent().prop("outerHTML");
        attr = attr.replace('addAttr', 'delAttr').replace('+', '-');
        $(a).parent().parent().after(attr);
    }
    // 属性输入框的加减事件
    function delAttr(a) {
        $(a).parent().parent().remove();
    }


    /** 以下 商品规格相关 js*/
    $(document).ready(function () {

        // 商品类型切换时 ajax 调用  返回不同的属性输入框
        $("#spec_type").change(function () {
            // var goods_id = '{$goodsInfo.goods_id}';
            var spec_type = $(this).val();
            $.ajax({
                type: 'GET',
                data: {spec_type: spec_type},
                url: "{{ url('admin/Goods/ajaxGetSpec') }}",
                success: function (data) {
                    // console.log(data);
                    $("#ajax_spec_data").html('')
                    $("#ajax_spec_data").append(data);

                    ajaxGetSpecInput();	// 触发完  马上处罚 规格输入框
                }
            });
        });
        // 触发商品规格
        $("#spec_type").trigger('change');
    });

    //获得二级分类
    $('#cat_id').change(function () {
        //清除商品分类列表
        $('#cat_id_2 option[value!="0"]').remove();
        $('#cat_id_3 option[value!="0"]').remove();
        //获取上级分类的ID
        var value = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{url('admin/Goods/categoryList/getCategory')}}",
            data: {'value': value, '_token': '{{csrf_token()}}'},
            dataType: 'json',
            success: function (data) {
                $('#cat_id_2').append(data);
            },
        });
    });

    //获得三级分类
    $('#cat_id_2').change(function () {
        //清除商品分类列表
        $('#cat_id_3 option[value!="0"]').remove();
        //获取上级分类的ID
        var value = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{url('admin/Goods/categoryList/getCategory')}}",
            data: {'value': value, '_token': '{{csrf_token()}}'},
            dataType: 'json',
            success: function (data) {
                $('#cat_id_3').append(data);
            },
        });
    });

    //商品缩略图片上传弹框
    function GetUploadify(num, elementid, path, callback) {
        var upurl = " {{url('admin/Goods/upload')}} ";
        var iframe_str = '<iframe frameborder="0" ';
        iframe_str = iframe_str + 'id=uploadify ';
        iframe_str = iframe_str + ' src=' + upurl;
        iframe_str = iframe_str + ' allowtransparency="true" class="uploadframe" scrolling="no"> ';
        iframe_str = iframe_str + '</iframe>';

        $("body").append(iframe_str);
        $("iframe.uploadframe").css("height", $(document).height()).css("width", "100%").css("position", "absolute").css("left", "0px").css("top", "0px").css("z-index", "999999").show();
        $(window).resize(function () {
            $("iframe.uploadframe").css("height", $(document).height()).show();
        });
    }

    function getUploadify1(num, elementid, path, callback) {
        var upurl = "{{url('admin/Goods/specUpload/')}}";
        // alert(upurl);
        var iframe_str = '<iframe frameborder="0" ';
        iframe_str = iframe_str + 'id=uploadify ';
        iframe_str = iframe_str + ' src=' + upurl;
        iframe_str = iframe_str + ' allowtransparency="true" class="uploadframe" scrolling="no"> ';
        iframe_str = iframe_str + '</iframe>';

        $("body").append(iframe_str);
        $("iframe.uploadframe").css("height", $(document).height()).css("width", "100%").css("position", "absolute").css("left", "0px").css("top", "0px").css("z-index", "999999").show();
        $(window).resize(function () {
            $("iframe.uploadframe").css("height", $(document).height()).show();
        });
    }

    function getUploadify2(num, elementid, path, callback) {
        var upurl = "{{url('admin/Goods/shopUpload/')}}";
        var iframe_str = '<iframe frameborder="0" ';
        iframe_str = iframe_str + 'id=uploadify ';
        iframe_str = iframe_str + ' src=' + upurl;
        iframe_str = iframe_str + ' allowtransparency="true" class="uploadframe" scrolling="no"> ';
        iframe_str = iframe_str + '</iframe>';

        $("body").append(iframe_str);
        $("iframe.uploadframe").css("height", $(document).height()).css("width", "100%").css("position", "absolute").css("left", "0px").css("top", "0px").css("z-index", "999999").show();
        $(window).resize(function () {
            $("iframe.uploadframe").css("height", $(document).height()).show();
        });
    }

</script>


</body></html>
