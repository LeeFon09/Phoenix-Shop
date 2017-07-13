<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>@yield('title')</title>
	<meta http-equiv="keywords" content="" />
	<meta name="description" content="" />
</head>
<body>
<!--------头部开始-------------->
@include('Home.Public.header')
<!--------头部结束-------------->
<script src='{{asset("/js/layer/layer.js")}}'></script>
@section('content')

@show

<!--------footer-开始-------------->
@include("Home.Public.footer")
<!--------footer-结束-------------->

@section('javascript')

@show
</body>
</html>