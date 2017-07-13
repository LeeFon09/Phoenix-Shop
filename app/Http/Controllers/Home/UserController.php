<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Http\Controllers\Home\RegController;


class UserController extends Controller
{
    /**
     * 显示我的商城
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $currUser = session('user');
        //用户会员等级
        $level = DB::table('user_level')->get();
        return view('Home.User.index', compact('currUser', 'level'));
    }

    /**
     * 个人信息页面遍历数据
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userInfo()
    {
        $user_id = session('user')->user_id;
        $userInfo = DB::table("users")->where("user_id", $user_id)->first();
//        dd($userInfo);
        //所有的省份
        $province = DB::table("region")->where(["level" => 1, "parent_id" => 0])->get();

        //用户所在城市
        $city = DB::table("region")->where(["level" => 2, "parent_id" => $userInfo->province])->get();

        //用户所在县
        $district = DB::table("region")->where(["level" => 3, "parent_id" => $userInfo->city])->get();

        return view('Home.User.userInfo', compact("userInfo", "province", "city", "district"));
    }

    /**
     * 上传图片
     * @param $num        上传个数
     * @param $elementid 上传图片名
     * @param $path      上传路径
     * @param $callback  回调函数
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajaxPic($num, $elementid, $path, $callback)
    {
        $info = array(
            'num' => $num,
            'title' => '凤凰涅槃专用下载',
//            'upload' =>U('Admin/Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'logo')),
            'upload' => 'home/uploadify/picDispose',
            'size' => '4M',
            'type' => 'jpg,png,gif,jpeg',
            'input' => $elementid,
            'func' => empty($callback) ? 'undefined' : $callback,
        );

        return view("Home.Uploadify.upload", compact("info"));
    }


    /**
     * 上传图片并保存到Public/upload/head_pic
     * @param Request $request
     * @return array
     */
    public function picDispose(Request $request)
    {
        $file = Input::file("Filedata");
        if ($file->isValid()) {

            //后缀名
            $extension = $file->extension();

            $newName = date("YmdHis") . mt_rand(1, 999) . "." . $extension;
            $year = date("Y");
            $time = date("m-d");
            $destinaltionPath = "Public\upload\head_pic\\" . $year . "\\" . $time;

            $path = $file->move($destinaltionPath, $newName);
            $fileName = $destinaltionPath . "\\" . $newName;

            $data = [
                "url" => $fileName,
                "state" => "SUCCESS"
            ];
            return $data;
        }

        $data = [
            "text" => "上传失败",
        ];
        return $data;
    }


    /**
     * 删除图片
     */
    public function delUpload()
    {
        $filename = Input::get("filename");
        unlink($filename);
    }

    /*
     * 保存头像图片到数据库中，并写入session-user中
     */
    public function saveImg()
    {
        $user = session("user");
        $userId = $user->user_id;
        $path = Input::get("filename");
        $res = DB::table("users")->where("user_id", $userId)->update(["head_pic" => $path]);
        if ($res) {
            $user->head_pic = $path;
            session(["user" => $user]);
        }
    }


    /**
     * 验证邮箱
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function validateEmail()
    {
        $user = session("user");
        $email = $user->email;
        return view("Home.User.validateEmail", compact("email"));
    }

    /**
     * 发送邮箱验证码，并将其写入session中，有效期10分钟
     * @param $step  用户id
     * @param $input 要发送的邮箱
     * @return array
     */
    public function emailCode($step, $input)
    {
        $code = mt_rand(0000, 9999);

        $data = [
            "email" => $input,
            "emailCode" => $code
        ];

        Mail::send("Home.User.sendEmail", $data, function ($mess) use ($data) {
            $mess->to($data["email"])->subject("凤凰涅槃");
        });
        session(['code' => $code]);
        session(['time' => time() + 600]);
        session(["email" => $input]);

        $info = [
            "status" => 1,
        ];
        return $info;
//        702378922@qq.com

    }

    /**
     * 下一步验证邮箱和验证码
     * @param $code 输入的验证码
     * @param $email 输入的邮箱
     * @return int|string
     */
    public function checkCode($code, $email)
    {
        $session_code = session("code");
        $time = session("time");
        $now = time();
        $session_email = session("email");

        if (!$session_code && !$time) {
            $data = "请重新进行邮箱验证";
            return $data;
        }

        if ($now - $time > 600) {
            $data = "验证码失效";
            return $data;
        }

        if ($code != $session_code) {
            $data = "验证码错误";
            return $data;
        }
        if ($email != $session_email) {
            $data = "邮箱不匹配";
            return $data;
        }

        session()->forget('code');
        session()->forget('time');
        session()->forget('email');

        $user = session("user");
        $id = $user->user_id;
        $user->email = $session_email;
        session(["user"=>$user]);

        $res = DB::table("users")->where("user_id", $id)->update(["email" => $session_email, "email_validated" => 1]);
        if ($res) {
            $data = 1;
            return $data;
        }

    }

    /**
     * 显示验证手机页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function validateMobile()
    {
        return view("Home.User.validateMobile");
    }

    /**
     * 获取手机验证码
     * @param $input
     * @return mixed
     */
    public function mobileCode($input)
    {
        $reg = new RegController;
        //短信接口地址
        $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
        //获取手机号
        $mobile = $input;
        //获取验证码
//        $send_code = $_POST['send_code'];
        //生成的随机数
        $mobile_code = $reg->random(4,1);
        if(empty($mobile)){
            exit('手机号码不能为空');
        }
        //防用户恶意请求
//        if(empty($_SESSION['send_code']) or $send_code!=$_SESSION['send_code']){
//            exit('请求超时，请刷新页面后重试');
//        }

        $post_data = "account=C87862856&password=b22b51d13f951949574b69a16789558e&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。");
        //用户名请登录用户中心->验证码、通知短信->帐户及签名设置->APIID
        //查看密码请登录用户中心->验证码、通知短信->帐户及签名设置->APIKEY
        $gets =  $reg->xml_to_array($reg->Post($post_data, $target));
        if($gets['code']==2){
            session(['mobile'=>$mobile]);
            session(['mobile_code' =>$mobile_code]);
            $data = [
                'status' => 0,
                'msg' => '发送成功'
            ];
            return $data;
        }

    }


    /**
     * 检验手机验证码
     * @return int
     */
    public function checkMobileCode()
    {
        $code = Input::get("code");
        $mobile = Input::get("mobile");

        $session_code = session("mobile_code");
        $session_mobile = session("mobile");

        if($code != $session_code && $mobile != $session_mobile){
            $info = "验证码错误";
            return $info;
        }

        if($mobile != $session_mobile){
            $info = "手机号错误";
            return $info;
        }

        session()->forget('mobile_code');
        session()->forget('mobile');

        $user = session("user");
        $id = $user->user_id;
        $user->mobile = $session_mobile;
        session(["user"=>$user]);

        $res = DB::table("users")->where("user_id", $id)->update(["mobile" => $session_mobile, "mobile_validated" => 1]);
        if($res){
            $data = 1;
            return $data;
        }

    }

    /**
     * 显示修改密码页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword()
    {
        return view("Home.User.password");
    }

    /**
     * 处理修改密码
     * @return int|mixed|string
     */
    public function ajaxPassword()
    {
        $data = Input::get("data");
        $str = urldecode($data);
        parse_str($str);
//        old_password=asd&new_password=asd&confirm_password=asd
        $id = session("user")->user_id;
        $pass = DB::table("users")->where("user_id", $id)->value("password");
        if(!Hash::check($old_password,$pass)){
            $data = "原密码不正确！";
            return $data;
        }

        if($new_password !== $confirm_password){
            $data = "新密码和确认密码必须相同";
            return $data;
        }
        $new_password = Hash::make($new_password);
        $res= DB::table("users")->where("user_id", $id)->update(["password" => $new_password]);
        if($res){
            $data = 1;
            return $data;
        }
    }

    /**
     * 保存用户信息到数据库
     * @return int
     */
    public function saveUserInfo()
    {
        $data = Input::get("data");
        $str = urldecode($data);
        parse_str($str);
        //q=&q=123&head_pic=/Public\upload\head_pic\2017\06-29\2017062900270560.png&nickname=七夜
        //&qq=8234124698&sex=0&province=1&city=2&district=3
        $user = session("user");
        $id = $user->user_id;
        $update = [
            "nickname" => $nickname,
            "qq"       => $qq,
            "sex"      => $sex,
            "province" => $province,
            "city"     => $city,
            "district" => $district
        ];
        $res = DB::table("users")->where("user_id", $id)->update($update);
//        return $res;
        if($res){
            $user->nickname = $nickname;
            $user->qq = $qq;
            $user->sex = $sex;
            $user->province = $province;
            $user->city = $city;
            $user->district = $district;
            session(["user"=>$user]);
            $info = 1;
            return $info;
        }
    }



    public function orderList()
    {
        return view('Home.User.orderList');
    }

    /**
     * 我的收藏
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function goodsCollect()
    {
        $lists = DB::table('goods')->select('original_img','goods_name','shop_price','goods.goods_id','collect_id')
            ->join('goods_collect','goods.goods_id','=','goods_collect.goods_id')
            ->where('goods_collect.user_id',session('user')->user_id)->get();
        return view('Home.User.goodsCollect',compact('lists'));
    }

    /**
     * 取消收藏
     * @param $id 商品goods_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelCollect($id)
    {
        DB::table('goods_collect')->where('collect_id',$id)->delete();
        return redirect()->action('Home\UserController@goodsCollect');
    }


    /**
     * 退换货商品遍历
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function returnGoodsList()
    {
        $currUser = session('user');
        $userId = $currUser->user_id;

        $returnGoods = DB::table("return_goods")->where(["user_id" => $userId])
            ->orderBy("id","desc")->get();
        foreach($returnGoods as $k=>$v){
            $goods_id = $v->goods_id;
            $goods_name = DB::table("goods")->select("goods_name")->where("goods_id",$goods_id)->first();
            if(!$goods_name){
                $goods_name = (object)[];
                $goods_name->goods_name = "商品异常";
            }
            $v->goods_name = $goods_name->goods_name;
        }
        return view('Home.User.returnGoodsList', compact("returnGoods"));
    }

    public function returnGoodsAdd(Request $request,$goodsId)
    {
//        dump($request->input('orderid'),$request->input('ordersn'),$goodsId);
        $currUser = session('user');
        $data = [
            "order_id"=>$request->input('orderid'),
            "order_sn"=>$request->input('ordersn'),
            "goods_id"=>$goodsId,
            "user_id"=>$currUser->user_id,
            "addtime"=>time()
        ];
//        dd($data);
        DB::table('return_goods')->insert($data);
        return redirect()->action('Home\UserController@returnGoodsList');
    }
/*
`id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '退货申请表id自增',
`order_id` INT(11) NULL DEFAULT '0' COMMENT '订单id',
`order_sn` VARCHAR(1024) NULL DEFAULT '' COMMENT '订单编号' COLLATE 'utf8_general_ci',
`goods_id` INT(11) NULL DEFAULT '0' COMMENT '商品id',
`user_id` INT(11) NULL DEFAULT '0' COMMENT '用户id',
`type` TINYINT(1) NULL DEFAULT '0' COMMENT '0.生成记录，1退货2换货3退款',
`reason` VARCHAR(1024) NULL DEFAULT '' COMMENT '退换货原因' COLLATE 'utf8_general_ci',
`imgs` VARCHAR(512) NULL DEFAULT '' COMMENT '拍照图片路径' COLLATE 'utf8_general_ci',
`addtime` INT(11) NULL DEFAULT '0' COMMENT '申请时间',
`status` TINYINT(1) NULL DEFAULT '0' COMMENT '1申请中2客服理中3已完成',
`remark` VARCHAR(1024) NULL DEFAULT '' COMMENT '客服备注' COLLATE 'utf8_general_ci',
`spec_key` VARCHAR(64) NULL DEFAULT '' COMMENT '商品规格key 对应tp_spec_goods_price 表' COLLATE 'utf8_general_ci',*/

    /**
     * 退换货商品查看
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function returnGoodsInfo($id)
    {

        $returnGoodsInfo = DB::table("return_goods")->where("id", $id)->first();
        if($returnGoodsInfo->imgs){
            $returnGoodsInfo->imgs = explode(",", $returnGoodsInfo->imgs);
        }else{
            $returnGoodsInfo->imgs = [];
        }
        $goodsInfo = DB::table("goods")->select("original_img", "goods_name")
            ->where("goods_id", $returnGoodsInfo->goods_id)->first();
//        dump($goodsInfo);
//        dd($returnGoodsInfo);
        return view("Home.User.returnGoodsInfo", compact("returnGoodsInfo", "goodsInfo"));
    }


}
