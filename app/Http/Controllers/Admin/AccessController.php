<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Access;
use Illuminate\Support\Facades\DB; 
use App\Libs\Pages\Page;

class AccessController extends Controller
{
    /**
     * 显示所有的权限信息
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         //查询商品规格的总条数
        $totalRows = count(DB::table('accesses')->get());
        //创建一个分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Admin/access');
         $access = Access::orderby('id','desc')->where(function($query) use ($request, &$totalRows, &$pageObj){
                    //获取关键字
                    $keyword = $request->input('keyword');
                    //检测参数
                    if(!empty($keyword)) {
                        $query->where('name','like','%'.$keyword.'%');
                        $totalRows = count(DB::table('accesses')->where('name', 'like', '%'.$keyword.'%')->get());
                        $pageObj = new Page($totalRows, $request, 'admin/Admin/access',10,'p',['keyword'=>$keyword]);
                            }
                })->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        return view('Admin.Access.index')->with(['access' => $access, 'request'=>$request, 'pageObj'=>$pageObj]);
    }

    /**
     * 显示创建权限的表格
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('Admin.Access.add');
    }

    /**
     * 保存新添加的权限信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //验证表单信息
        $this->validate($request, [
            'name' => 'required|unique:accesses',
            'type' => 'required',
        ],
        [
            'name.required' => '权限名不能为空',
            'name.unique' => '已经存在该权限',
            'type.required' => '至少填写一个URL',
        ]);
        $access = new Access();
        $access->name = $request->name;
        $access->type = $request->type;
        if(!empty($request->desc)){
            $access->desc = $request->desc;
        }
        if($access->save()){
            return redirect(url('admin/Admin/access'))->with('info','添加成功');
        }else{
            return back()->with('info')->with('info','添加失败');
        }
    }


    /**
     * 显示修改权限的表格
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $access = Access::findOrFail($id);
        return view('Admin.Access.edit')->with('access',$access);
    }

    /**
     * 更新权限信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         //验证表单信息
        $this->validate($request, [
            'name' => 'required|unique:accesses',
            'type' => 'required',
        ],
        [
            'name.required' => '角色名不能为空',
            'name.unique' => '已经存在该权限',
            'type.required' => '至少填写一个URL',
        ]);
        $access = Access::findOrFail($id);
        $access->name = $request->name;
        $access->type = $request->type;
        if(!empty($request->desc)){
            $access->desc = $request->desc;
        }
        if($access->save()){
            return redirect(url('admin/Admin/access'))->with('info','修改成功');
        }else{
            return back()->with('info')->with('info','修改失败');
        }
    }

    /**
     * 删除权限信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del ($id)
    {
        $access = Access::findOrFail($id);
        if($access->delete()){
            DB::table('role_access')->where('access_id', $id)->delete();
            return redirect(url('admin/Admin/access'))->with('info', '删除成功');
        }else{
            return back()->with('info', '删除失败');
        }

    }
}
