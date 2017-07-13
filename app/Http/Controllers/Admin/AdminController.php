<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\Admin;
use Hash;
use App\Libs\Pages\Page;

class AdminController extends Controller
{
    /**
     * 显示所有的管理员信息
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //查询管理员的总条数
        $totalRows = count(DB::table('admin')->get());
        //创建一个分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Admin/index');
        $admin = DB::table('admin')->where(function($query) use($request, &$totalRows, &$pageObj) {
            if(!empty($request->keywords)){
                $query->where('user_name', 'like', '%'.$request->keywords.'%');
                $totalRows = count(DB::table('admin')->where('user_name', 'like', '%'.$request->keywords.'%')->get());
                $pageObj = new Page($totalRows, $request, 'admin/Admin/index',10,'p',['keywords'=>$request->keywords]);
            }
        })->where('admin_id','<>', 1)->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        foreach($admin as $k => $v){
            $role_id = DB::table('user_role')->where('uid', $v->admin_id)->value('role_id');
            $admin[$k]->role = DB::table('roles')->where('id', $role_id)->value('name');
        }
        return view('Admin.Admin.index', compact('admin', 'pageObj'));
    }

    /**
     * 显示创建管理员的信息
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = DB::table('roles')->get();
        return view('Admin.Admin.add', compact('role'));
    }

    /**
     * 保存新添加的管理员信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //验证表单信息
         $this->validate($request, [
            'user_name' => 'required|unique:admin|max:255',
            'password' => 'required|regex:/\w{6,20}/|same:repassword',
            'email' => 'required|email|unique:admin',
            'role_id' => 'required',
        ],
        [
            'user_name.required' => '用户名不能为空',
            'user_name.unique' => '已经存在该用户',
            'password.required' => '密码不能够为空',
            'password.regex' => '密码必须为6~20个数字、字母或下划线',
            'password.same' => '两次密码不一样',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'email.unique' => '该邮箱已绑定账号',
            'role_id.required' => '请选择一个角色',
        ]);

        $admin = new Admin();
        $admin->user_name = $request->user_name;
        $admin->password = Hash::make($request->password);
        $admin->email = $request->email;
        $admin->add_time = time();
        //执行插入
        if($admin->save()) {
            $aid = DB::table('admin')->where('user_name', $admin->user_name)->value('admin_id');
            DB::table('user_role')->insert(['role_id'=>$request->role_id, 'uid'=>$aid]);
            return redirect(url('admin/Admin/index'))->with('info', '添加成功');
        }else{
            return back()->with('info', '添加失败');
        }
    }


    /**
     * 显示修改管理员信息的表格
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //读取用户的信息
        $admin = DB::table('admin')->where('admin_id', $id)->get();
        $role = DB::table('roles')->get();
        return view('Admin.Admin.edit', compact('admin','role'));
    }

    /**
     * 更新管理员信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       //验证表单信息
         $this->validate($request, [
            'user_name' => 'required|unique:admin|max:255',
            'password' => 'required|regex:/\w{6,20}/|same:repassword',
            'email' => 'required|email',
            'role_name' => 'required',
        ],
        [
            'user_name.required' => '用户名不能为空',
            'user_name.unique' => '已经存在该用户',
            'password.required' => '密码不能够为空',
            'password.regex' => '密码必须为6~20个数字、字母或下划线',
            'password.same' => '两次密码不一样',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'role_name.required' => '请选择一个角色',
        ]);
        $admin = Admin::find($request->admin_id);
        $admin->user_name = $request->user_name;
        $admin->password = Hash::make($request->password);
        $admin->email = $request->email;
        //执行插入
        if($admin->save()) {
            $role_id = DB::table('roles')->where('name', $request->role_name)->value('id');
            DB::table('user_role')->where('uid', $request->admin_id)->update(['role_id' => $role_id]);
            return redirect(url('admin/Admin/index'))->with('info', '修改成功');
        }else{
            return back()->with('info', '修改失败');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del ($admin_id)
    {
         //创建模型
        $admin = Admin::findOrFail($admin_id);
        //删除
        if($admin->delete()) {
            DB::table('user_role')->where('uid', $admin_id)->delete();
            return back()->with('info','删除成功');
        }else{
            return back()->with('info','删除失败');
        }
    }
}
