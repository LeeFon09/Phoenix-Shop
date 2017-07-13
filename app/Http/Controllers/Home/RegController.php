<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\Mail;

class RegController extends Controller
{
    /**
     * POST方式是用户注册的操作，GET方式是显示用户注册页面
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function reg(Request $request)
    {
        //POST:注册
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $code = new \Code;
            $_code = $code->get();
            $verify_code = $input['verify_code'];
            $reg_time = time();
            if (strtolower($verify_code) == strtolower($_code)) {
                $username = $input['username'];
                //手机注册
                if(is_numeric($username)){
                    $phone = $input['username'];
                    $code = $input['code'];
                    $phone_code = session('phone_code');
                    if($code == $phone_code){
                        $password = Hash::make($input['password']);
                        $re = DB::table('users')->insert(['mobile' => $phone, 'password' => $password, 'reg_time' => $reg_time]);
                        if($re){
                            return redirect('login');
                        }else{
                            return back();
                        }
                    }else{
                        return back();
                    }
                }else{
                    //邮箱验证
                    $sessionUsername = session('email');
                    $emailCode = $input['code'];
                    $sessionCode = session('code');
                    $time = time();
                    $sessionTime = session('time');
                    if ($username == $sessionUsername && $emailCode == $sessionCode && $sessionTime - $time > 0) {
                        $password = Hash::make($input['password']);
                        $re = DB::table('users')->insert(['email' => $username, 'password' => $password, 'reg_time' => $reg_time]);
                        if($re){
                            return redirect('login');
                        }else{
                            return back();
                        }
                    } else {
                        return back();
                    }
                }
            } else {
                return back();
            }
        }
//        dd(Hash::make("asdasd"));
        //GET:显示注册页
        return view('Home.User.reg');
    }

    /**
     * 判断手机号码或邮箱是否可用的操作
     *
     * @param Request $request
     * @return array
     */
    public function checkUsername(Request $request)
    {
        if ($request->isMethod('POST')) {
            $input = $request->except('_token');
            $username = $input['username'];
            //判断手机号是否可用
            if (is_numeric($username)) {
                $user = DB::table('users')->where('mobile', $username)->first();
                if (empty($user)) {
                    $data = [
                        'status' => 0,
                        'msg' => '手机号可用'
                    ];
                    return $data;
                } else {
                    $data = [
                        'status' => 1,
                        'msg' => '此手机号已存在'
                    ];
                    return $data;
                }
            } else {//判断邮箱是否可用
                $user = DB::table('users')->where('email', $username)->first();
                if (empty($user)) {
                    $data = [
                        'status' => 0,
                        'msg' => '邮箱可用'
                    ];
                    return $data;
                } else {
                    $data = [
                        'status' => 1,
                        'msg' => '此邮箱已存在'
                    ];
                    return $data;
                }
            }
        }
    }

    /**
     * 发送邮箱验证码的操作
     *
     * @param Request $request
     * @return array
     */
    public function emailCode(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $toEmail = $input['send'];
            $num = mt_rand(0000, 9999);
            session(['email' => $toEmail]);
            session(['code' => $num]);
            session(['time' => time() + 600]);
            $emailCode = $num;
            $data = ['email' => $toEmail, 'emailCode' => $emailCode];
            Mail::send('Home.User.sendEmail', $data, function ($message) use ($data) {
                $message->to($data['email'])->subject('欢迎注册邮箱账号');
            });
            $data = [
                'status' => 1,
                'msg' => '发送成功'
            ];
            return $data;
        }
    }

    /**
     * 发送邮箱验证码的模板
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sendEmail()
    {
        return view('Home.User.sendEmail');
    }

    /**
     * 请求数据到短信接口，检查环境是否开启了 curl init。
     *
     * @param $curlPost
     * @param $url
     * @return mixed
     */
    public function Post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    /**
     * 将xml数据转换为数组格式。
     *
     * @param $xml
     * @return mixed
     */
    public function xml_to_array($xml){
        $reg = "/<(\w+)[^-->]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    /**
     * random()函数是返回随机整数。
     *
     * @param int $length
     * @param int $numeric
     * @return string
     */
    public function random($length = 6 , $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }

    /**
     * 生成手机短信验证码并发送
     *
     * @param Request $request
     * @return array
     */
    public function getMsg (Request $request)
    {
        //短信接口地址
        $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
        //获取手机号
        $input = $request->except('_token');
        $mobile = $input['mobile'];
        //获取验证码
        // $send_code = $_POST['send_code'];
        //生成的随机数
        $mobile_code = $this->random(4,1);
        if(empty($mobile)){
            exit('手机号码不能为空');
        }
        //防用户恶意请求
        // if(empty($_SESSION['send_code']) or $send_code!=$_SESSION['send_code']){
        //     exit('请求超时，请刷新页面后重试');
        // }

        $post_data = "account=C19399224&password=ea4c49e225fd3b1a1b96f530d34b1655&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。");
        //用户名是登录ihuyi.com账号名（例如：cf_demo123）
        //查看密码请登录用户中心->验证码、通知短信->帐户及签名设置->APIKEY
        $gets =  $this->xml_to_array($this->Post($post_data, $target));
        if ($gets['code'] == 2) {
            session(['phone_code' => $mobile_code]);
            $data = [
                'status' => 0,
                'msg' => '发送成功'
            ];
            return $data;
        }
    }

    /**
     *验证手机验证码是否一致
     */
    public function checkMsg ()
    {
        $phone_code = Input::get('phone_code');
        if($phone_code == session('phone_code')){
            echo '验证正确';
        }else{
            echo '验证失败';
        }
    }
}
