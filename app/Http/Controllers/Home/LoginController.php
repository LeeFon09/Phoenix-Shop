<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if($request->isMethod('post')){
            $input = $request->except('_token');
            $verify_code = $input['verify_code'];
            $code = new \Code;
            $_code = $code->get();
            if(strtolower($verify_code) == strtolower($_code)){
                $username = $input['username'];
                if(is_numeric($username)){
                    $user = DB::table('users')->where('mobile', $username)->first();
                    if(empty($user)){
                        $data = [
                            'status' => 2,
                            'msg' => '账号或密码错误'
                        ];
                        return $data;
                    }else{
                        $password = $input['password'];
                        if (Hash::check($password, $user->password)) {

                            session(['user' => $user]);
                            $data = [
                                'status' => 0,
                                'msg' => '恭喜，登陆成功'
                            ];
                            return $data;
                        } else {
                            $data = [
                                'status' => 3,
                                'msg' => '账号或密码错误'
                            ];
                            return $data;
                        }
                    }
                }else{
                    $user = DB::table('users')->where('email', $username)->first();
                    if(empty($user)){
                        $data = [
                            'status' => 2,
                            'msg' => '账号或密码错误'
                        ];
                        return $data;
                    }else{
                        $password = $input['password'];
                        if (Hash::check($password, $user->password)) {
                            session(['user' => $user]);
                            $data = [
                                'status' => 0,
                                'msg' => '恭喜，登陆成功'
                            ];
                            return $data;
                        } else {
                            $data = [
                                'status' => 3,
                                'msg' => '账号或密码错误'
                            ];
                            return $data;
                        }
                    }
                }
            }else{
                $data = [
                    'status' => 1,
                    'msg' => '验证码错误'
                ];
                return $data;
            }
        }else {
            return view('Home.User.login');
        }
    }

    //获取验证码
    public function code()
    {
        $code = new \Code;
        $code->make();
    }

    public function logout(Request $request){
        if ($request->session()->exists("user")){
            $request->session()->forget('user');
        }
        return redirect('/');
    }

}
