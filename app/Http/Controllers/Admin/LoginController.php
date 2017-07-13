<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    //验证登陆
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $vertify = $input['vertify'];
            $code = new \Code;
            $_code = $code->get();
            //验证验证码
            if (strtolower($vertify) == strtolower($_code)) {
                $username = $input['username'];
                $admin = DB::table('admin')->where('user_name', $username)->first();
                //验证账号
                if (empty($admin)) {
                    $data = [
                        'status' => 1,
                        'msg' => '账号或密码错误'
                    ];
                    return $data;
                } else {
                    //验证密码
                    $password = $input['password'];
                    if (Hash::check($password, $admin->password)) {
                        session(['admin' => $admin]);
                        $data = [
                            'status' => 0,
                            'msg' => '恭喜，登陆成功'
                        ];
                        return $data;
                    } else {
                        $data = [
                            'status' => 2,
                            'msg' => '账号或密码错误'
                        ];
                        return $data;
                    }
                }
            } else {
                $data = [
                    'status' => 3,
                    'msg' => '验证码错误'
                ];
                return $data;
            }
        } else {
            return view('Admin.Admin.login');
        }

    }

    //获取验证码
    public function code()
    {
        $code = new \Code;
        $code->make();
    }

    //安全退出
    public function quit()
    {
        session(['admin' => null]);
        return redirect('admin/login');
    }
}
