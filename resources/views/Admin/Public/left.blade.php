<div class="main-sidebar" style="overflow-y:auto;">
      <div class="sidebar">
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </form>
        <!-- /.search form -->
        <ul class="sidebar-menu"> 
	      <!--<foreach name="menu_list" item="vo" key="k" >
	      	<notempty name="vo.sub_menu">-->
            @foreach($menu as $v)
        	<li class="treeview">
        	    <a href="javascript:void(0)">
	              <i class="fa {{$v["icon"]}}"></i><span>{{$v["name"]}}</span><i class="fa fa-angle-left pull-right"></i>
	            </a>
	            <ul class="treeview-menu">
                    @foreach($v["sub_menu"] as $vv)
                        {{--{{dd($vv["control"]."/".$vv["act"])}}--}}
	            	<li onclick="makecss(this)" data-id="{{$vv["act"]}}_{{$vv["control"]}}">
	            		<a href='{{asset("admin/".$vv["control"]."/".$vv["act"])}}' target='rightContent'><i class="fa fa-circle-o"></i>{{$vv["name"]}}</a>
	            	</li>
                    @endforeach
	            </ul>
        	</li>
            @endforeach
        	<!--</notempty>
	      </foreach> -->
        </ul>
      </div>
</div>
