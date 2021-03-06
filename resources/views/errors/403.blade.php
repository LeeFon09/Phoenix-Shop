<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>403错误</title>
<meta http-equiv="keywords" content="" />
<meta name="description" content="" />
</head>
<style type="text/css">
.concenter-err {width: 100%;background: url({{asset('Static/images/errorbg_404.png')}});}
#warpper {width: 1220px;margin: 0 auto;position: relative;clear: both;font-family: "微软雅黑";}
.clearfix {zoom: 1;}
.clearfix:after, .clearfix:before {display: block;overflow: hidden;height: 0;content: '\0020';}
.error-pic {background: url({{asset('Static/images/error_pic.png')}}) no-repeat;}
.error-page {width: 708px;height: 641px;margin: 44px auto 0;text-align: center;}
.error-page-mb37 {margin-bottom: 37px;}
.error-page-txt {padding-top: 391px;margin-bottom: 29px;font-family: \5FAE\8F6F\96C5\9ED1;}
.error-page .error-page-txt h3 {font-size: 36px;color: #3B3B3B;font-weight: 900;padding-top: 6px;padding-bottom: 27px;}
.error-page-txt .error-page-pl48 {padding-left: 48px;}
.error-page .error-page-txt p {font-size: 16px;color: #6B6B6B;padding-left: 56px;}
.error-page-btn {height: 32px;padding-left: 26px;}
.error-page-btn a.return-back {background-position: 0 0;}
.error-page-btn a.return-index {background-position: 0 -66px;}
.error-page-btn a.return-back:hover {background-position: 0 -33px;}
.error-page-btn a.return-index:hover {background-position: 0 -99px;}
.clearfix:after {clear: both;}
.error-page-btn a {display: inline-block;width: 120px;height: 32px;margin: 0 15px;background-image: url({{asset('Static/images/error_btn.png')}});line-height: 32px;font-size: 0;}
</style>
<body>
@include('Home.Public.header')
<div class="concenter-err">
  <div id="warpper" class="clearfix">
    <div class="error-page error-pic">
      <div class="error-page-txt error-page-mb37">
        <h3 class="error-page-pl48">抱歉！页面无法访问...</h3>
        <p>{{$exception->getMessage()}}</p>
      </div>
      <div class="error-page-btn">
      	<a href="javascript:history.back();" class="return-back">返回上一页</a>
      	<a href="/" class="return-index">返回首页</a>
      </div>
    </div>
   </div>
</div>
@include("Home.Public.footer")
</body>
</html>