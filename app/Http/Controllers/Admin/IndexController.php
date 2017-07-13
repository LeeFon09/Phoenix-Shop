<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Admin;

class IndexController extends Controller
{
    /**
     * 后台首页的权限判断操作
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $uid = session('admin')->admin_id;
        if($uid!=1){
            $rid = @DB::table('user_role')->where('uid', $uid)->value('role_id');
            $aid = @DB::table('role_access')->where('role_id', $rid)->pluck('access_id');
            if(!empty($aid)){
                foreach($aid as $v){
                    $url[] = @DB::table('accesses')->where('id', $v)->value('type');
                }
            }else{
                $url = ['1','2'];
            }  
        }else{
            $url = ['1','2'];
        }
        session(['url'=>$url]);
        $admin = session('admin');
        return view('Admin.Index.index', compact('admin'));
    }

    /**
     * 修改密码的操作
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function pass(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $request->except('_token', 'act');
            $admin_id = $input['admin_id'];
            $password = Hash::make($input['password']);
            DB::table('admin')->where('admin_id', $admin_id)->update(['password' => $password]);
            return redirect('admin/welcome');
        }else{
            $admin = session('admin');
            return view('Admin.Admin.admin_info', compact('admin'));
        }
    }

    /**
     * 后台首页的显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome()
    {
        $sys_info = $this->get_sys_info();
        $arr = [
            'handle_status' => 2,
            'order_status' => 3
        ];
        $count['handle_order'] = DB::table('order')->where($arr)->count();//待处理订单
        $count['goods'] = DB::table('goods')->count();//商品总数
        $count['article'] = DB::table('article')->count();//文章总数
        $count['users'] = DB::table('users')->count();//会员总数
        $today = strtotime("-1 day");
        $time = date('Y-m-d', time());
        $start_time = $time . ' 00:00:00';
        $end_time = $time . ' 23:59:59';
        $count['new_order'] = DB::table('order')->whereDate('commit_time', '>=', $start_time)->whereDate('commit_time', '<=', $end_time)->count();//新增订单
        $count['today_login'] = DB::table('users')->where('last_login', '>', $today)->count();//今日访问
        $count['new_users'] = DB::table('users')->where('reg_time', '>', $today)->count();//新增会员
        $count['comment'] = DB::table('comment')->where('is_show', '=', 0)->count();//最新评论
        return view('Admin.Index.welcome', compact('sys_info', 'count'));
    }

    /**
     * 获取系统环境信息
     *
     * @return mixed
     */
    public function get_sys_info(){
        $sys_info['os']             = PHP_OS;
        $sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
        $sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off
        $sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        $sys_info['curl']			= function_exists('curl_init') ? 'YES' : 'NO';
        $sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['phpv']           = phpversion();
        $sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
        $sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
        $sys_info['max_ex_time'] 	= @ini_get("max_execution_time").'s'; //脚本最大执行时间
        $sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
        $sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
        $sys_info['memory_limit']   = ini_get('memory_limit');
        if(function_exists("gd_info")){
            $gd = gd_info();
            $sys_info['gdinfo'] = $gd['GD Version'];
        }else {
            $sys_info['gdinfo'] = "未知";
        }
        return $sys_info;
    }

}
