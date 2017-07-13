	<footer class="main-footer">
	   <div class="pull-right hidden-xs">
	    	 欢迎使用凤凰涅槃开源项目<b></b>
	   </div>
	   <strong>凤凰涅槃 &copy; 2017-2027 <a href="http://www.tp-shop.cn">凤凰涅槃旗下产品</a>.</strong>保留所有权利。
	</footer>

     <!-- Control Sidebar -->
     <aside class="control-sidebar control-sidebar-dark">
       <!-- Create the tabs -->
       <!--
       <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
         <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-at"></i></a></li>
         <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
       </ul>
       -->
       <!-- Tab panes -->
       <div class="tab-content">
      	<!-- Home tab content -->
         <div class="tab-pane" id="control-sidebar-home-tab">
         </div><!-- /.tab-pane -->
         <!-- Stats tab content -->
         <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
         <!-- Settings tab content -->
         <div class="tab-pane" id="control-sidebar-settings-tab">
         </div>
       </div>
     </aside>
   <div class="control-sidebar-bg"></div>
</div>

<script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('plugins/slimScroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('plugins/fastclick/fastclick.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('dist/js/app.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('dist/js/demo.js')}}" type="text/javascript"></script>
 
<script type="text/javascript">
$(document).ready(function(){
	$("#riframe").height($(window).height()-100);//浏览器当前窗口可视区域高度
	$("#rightContent").height($(window).height()-100);
	$('.main-sidebar').height($(window).height()-50);
});

var tmpmenu = 'index_Index';
function makecss(obj){
	$('li[data-id="'+tmpmenu+'"]').removeClass('active');
	$(obj).addClass('active');
	tmpmenu = $(obj).attr('data-id');
}

function callUrl(url){
	layer.closeAll('iframe');
	rightContent.location.href = url;
}
    var now_num = 0; //现在的数量
    var is_close=0;
    function ajaxOrderNotice(){
        var url = '{:U("Order/ajaxOrderNotice")}';
        if(is_close > 0)
            return;
        $.get(url,function(data){
            //有新订单且数量不跟上次相等 弹出提示
            if(data > 0 && data != now_num){
                now_num = data;
                if(document.getElementById('ordfoo').style.display == 'none'){
                    $('#orderAmount').text(data);
                    $('#ordfoo').show();
                }
            }
        })
//        setTimeout('ajaxOrderNotice()',5000);
    }
//setTimeout('ajaxOrderNotice()',5000);
</script>
<!-- 新订单提醒-s -->
<style type="text/css">
.fl{ float:left; margin-left:10px; margin-top:4px}
.fr{ float:right; margin-right:10px; margin-top:3px}
.orderfoods{ width:200px; border:1px solid #dedede; position:absolute; bottom:50px; z-index:999; right:10px; background-color:#00A65A;opacity:0.8;-webkit-opacity:0.8;filter:alpha(opacity=80);-moz-opacity:0.8;  }
.dor_head{ border-bottom:1px solid #dedede; height:28px; color:#FFF; font-size:12px}
.dor_head:after{ content:""; clear:both; display:block}
.dor_foot{ margin-top:6px; color:#FFF}
.dor_foot p{ padding:0 12px}
.te-in{ text-indent:2em;}
.dor_foot p span{ color:red}
.te-al-ce{ text-align:center}
</style>

<script type="text/javascript">
	function closes(){
        is_close = 1;
		document.getElementById('ordfoo').style.display = 'none';
	}
</script>
<!-- 新订单提醒-e -->
</body>
</html>
