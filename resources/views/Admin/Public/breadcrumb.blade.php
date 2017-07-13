<div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
		@if(!empty($menu))
			<li><a href=""><i class="fa fa-home"></i>&nbsp;&nbsp;{{$menu['parent']}}</a></li>
			<li><a href="{{url($menu['son_url'])}}">{{$menu['son']}}</a></li>
		@else
			<li><a href="{{url('admin')}}" target="_top"><i class="fa fa-home"></i>&nbsp;&nbsp;后台首页</a></li>
		@endif
	</ol>
</div>
