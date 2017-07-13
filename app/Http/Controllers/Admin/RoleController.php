<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Role;
use App\Access;
use Illuminate\Support\Facades\DB; 
use App\Libs\Pages\Page;

class RoleController extends Controller
{
    /**
     * 显示所有的角色信息
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //查询角色的总条数
        $totalRows = count(DB::table('roles')->get());
        //创建一个分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Admin/role');
        $role = Role::orderby('id','desc')->where(function($query) use ($request, &$totalRows, &$pageObj){
                    //获取关键字
                    $keywords = $request->input('keywords');
                    //检测参数
                    if(!empty($keywords)) {
                        $query->where('name','like','%'.$keywords.'%');
                        $totalRows = count(DB::table('roles')->where('name', 'like', '%'.$keywords.'%')->get());
                        $pageObj = new Page($totalRows, $request, 'admin/Admin/role',10,'p',['keywords'=>$request->keywords]);
                            }
                })->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        foreach($role as $k=>$v){
            $aid = DB::table('role_access')->where('role_id', $v->id)->get();
            if(!empty($aid)){
                foreach($aid as $v2){
                    $role[$k]->access .= @DB::table('accesses')->where('id', $v2->access_id)->value('name');
                }             
            }        
        }
        return view('admin.Role.index')->with(['role' => $role, 'request' => $request, 'pageObj' => $pageObj]);
    }

    /**
     * 显示创建一个角色的表格
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $access = Access::get();
        return view('Admin.Role.add')->with('access', $access);
    }

    /**
     * 保存新添加的角色信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //验证表单信息
        $this->validate($request, [
            'name' => 'required|unique:roles',
        ],
        [
            'name.required' => '角色名不能为空',
            'name.unique' => '已经存在改角色',
        ]);
        $role = new Role();
        $role->name = $request->name;
        if($role->save()){
            if(!empty($request->access)){
                $rid = DB::table('roles')->where('name', $role->name)->first()->id;
                foreach($request->access as $v){
                    DB::table('role_access')->insert(['role_id'=>$role->id, 'access_id'=>$v]);
                }
            }
            return redirect(url('admin/Admin/role'))->with('info','添加成功');
        }else{
            return back()->with('info')->with('info','添加失败');
        }
    }


    /**
     * 显示修改角色信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $access = Access::get();
        return view('admin.role.edit')->with(['role'=>$role,'access'=>$access]);
    }

    /**
     * 更新角色信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //验证表单信息
        $this->validate($request, [
            'name' => 'required|unique:roles',
        ],
        [
            'name.required' => '角色名不能为空',
            'name.unique' => '已经存在改角色',
        ]);
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        if($role->save()){
             if(!empty($request->access)){
                DB::table('role_access')->where('role_id', $id)->delete();
                $rid = DB::table('roles')->where('name', $role->name)->first()->id;
                foreach($request->access as $v){
                    DB::table('role_access')->insert(['role_id'=>$role->id, 'access_id'=>$v]);
                }
            }
            return redirect(url('admin/Admin/role'))->with('info','修改成功');
        }else{
            return back()->with('info')->with('info','修改失败');
        }
    }

    /**
     * 删除角色信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del ($id)
    {
        $role = Role::findOrFail($id);
        if($role->delete()){
            DB::table('user_role')->where('role_id', $id)->delete();
            DB::table('role_access')->where('role_id', $id)->delete();
            return redirect(url('admin/Admin/role'))->with('info', '删除成功');
        }else{
            return back()->with('info', '删除失败');
        }
    }
}
