<?php

namespace App\Http\Controllers\Admin\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 

class CategoryListController extends Controller
{
    /**
     * 显示所有的商品分类信息
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //声明两个全局变量
        global  $cat_list1, $cat_list;
        //查出所有的商品类型
        $cat_list1 = DB::table('goods_category')->get()->toArray();
        //遍历所有的商品类型以及商品类型的子类
        foreach($cat_list1 as $key => $value){
         //如果商品类型的等级为1，说明该商品类型还有子类型   
         if($value->level == 1){
             //获取该类型的子类型
             $this->getCatTree($value->id, $value);
         }
        }
        // dd($cat_list);
        return view('Admin.Goods.categoryList', compact('cat_list'));
    }

    /**
     * 显示创建一个商品分类的表格
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //查询所有等级为1的商品类型
        $cat_list = DB::table('goods_category')->where('level', 1)->get();
        //将查询到的信息返回给模板
        return view('Admin.Goods.addCategory', compact('cat_list'));
    }

    /**
     * 保存一个新的商品分类信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //表单验证
        $this->validate($request, [
            'name' => 'required|unique:goods_category',
            'mobile_name' => 'required|unique:goods_category',
        ],[
            'name.required' => '商品分类名不能省略',
            'name.unique' => '已经存在该商品分类',
            'mobile_name.unique' => '已经存在该手机端分类名称',
            'mobile_name.required'=>'手机端分类名称不能为空',
        ]);
         //获取修改后的商品类型信息
        $arr['name'] = $request->input('name');
        $arr['mobile_name'] = $request->input('mobile_name');
        //判断修改后的商品类型所属等级分类
        if(!empty($request->input('parent_id_2'))){
            //如果该商品类型属于第三级分类，则将它的二级分类ID赋给该分类的父ID
            $arr['parent_id'] = $request->input('parent_id_2');
            //拼接该商品类型的路径
            $arr['parent_id_path'] = '0_'.$request->input('parent_id_1').'_'.$request->input('parent_id_2');
            //商品等级
            $arr['level'] = 3;
        }else if(!empty($request->input('parent_id_1'))){
            //如果该商品类型属于第二级分类，则将它的一级分类ID赋给该分类的父ID
            $arr['parent_id'] = $request->input('parent_id_1');
            //拼接该商品类型的路径
            $arr['parent_id_path'] = '0_'.$request->input('parent_id_1');
            //商品等级
            $arr['level'] = 2;
        }else{
             //如果该商品类型属于第一级分类，则将0赋给该分类的父ID
            $arr['parent_id'] = 0;
            //拼接该商品类型的路径
            $arr['parent_id_path'] = '0_';
            //商品等级
            $arr['level'] = 1;
        }
        $arr['is_show'] = $request->input('is_show');
        $arr['cat_group'] = $request->input('cat_group');
        $arr['sort_order'] = $request->input('sort_order');
        //检测是否有文件上传
        if($request->hasFile('picname')) {
            //拼接文件夹路径
            $destinationPath = './admins/upload/'.date('Y-m-d').'/';
            //拼接文件路径
            $fileName = time().rand(100000, 999999);
            //获取上传文件的后缀
            $suffix = $request->file('picname')->getClientOriginalExtension();
            //文件的完整的名称
            $fullName = $fileName.'.'.$suffix;
            //保存文件
            $request->file('picname')->move($destinationPath, $fullName);
            //拼接文件上传之后的路径  
            $arr['image'] = $destinationPath.$fullName;
           
        }else{
            $arr['image'] = '';
        }
        //开始一个事务
        DB::beginTransaction();
        //插入商品类型信息
        $bool = DB::table('goods_category')->insert([
                'name'=>$arr['name'],
                'mobile_name'=>$arr['mobile_name'],
                'parent_id'=>$arr['parent_id'],
                'level'=>$arr['level'],
                'is_show'=>$arr['is_show'],
                'cat_group'=>$arr['cat_group'],
                'sort_order'=>$arr['sort_order'],
                'image'=>$arr['image'],
            ]);
        //更新新插入商品分类的路径
        $id = DB::table('goods_category')->where('image',$arr['image'])->value('id');
        $arr['parent_id_path'] = $arr['parent_id_path'].'_'.$id;
        $bool2 = DB::table('goods_category')->where('id',$id)->update(['parent_id_path'=>$arr['parent_id_path']]);
        //判断所有的数据库操作是否都正确
        if($bool == true && $bool2 == true){
            //所有的数据库操作都正确，提交事务
            DB::commit();
            return redirect(url('admin/Goods/categoryList'))->with('info','添加成功');
        }else{
            //数据库操作失败，执行事务回滚
            DB::rollBack();
            return back()->with('info','添加失败');
        }
    }

    /**
     * 显示修改商品分类的表格
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //根据商品类型ID查询该类型的所有信息
        $goods_category_info = DB::table('goods_category')->where('id', $id)->get();
        //查询所有等级为1的商品类型
        $cat_list = DB::table('goods_category')->where('level', 1)->get();
        //将查询到的信息返回给模板
        return view('Admin.Goods.editCategory', compact('goods_category_info','cat_list'));
    }

    /**
     * 更新商品分类的信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	//表单验证
        $this->validate($request, [
            'name' => 'required',
            'mobile_name' => 'required',
        ],[
            'name.required' => '商品分类名不能省略',
            'mobile_name.required'=>'手机端分类名称不能为空',
        ]);
        //获取修改后的商品类型信息
        $arr['name'] = $request->input('name');
        $arr['mobile_name'] = $request->input('mobile_name');
        //判断修改后的商品类型所属等级分类
        if(!empty($request->input('parent_id_2'))){
            //如果该商品类型属于第三级分类，则将它的二级分类ID赋给该分类的父ID
            $arr['parent_id'] = $request->input('parent_id_2');
            //拼接该商品类型的路径
            $arr['parent_id_path'] = '0_'.$request->input('parent_id_1').'_'.$request->input('parent_id_2').'_'.$id;
            //商品等级
            $arr['level'] = 3;
        }else if(!empty($request->input('parent_id_1'))){
            //如果该商品类型属于第二级分类，则将它的一级分类ID赋给该分类的父ID
            $arr['parent_id'] = $request->input('parent_id_1');
            //拼接该商品类型的路径
            $arr['parent_id_path'] = '0_'.$request->input('parent_id_1').'_'.$id;
            //商品等级
            $arr['level'] = 2;
        }else{
             //如果该商品类型属于第一级分类，则将0赋给该分类的父ID
            $arr['parent_id'] = 0;
            //拼接该商品类型的路径
            $arr['parent_id_path'] = '0_'.$id;
            //商品等级
            $arr['level'] = 1;
        }
        $arr['is_show'] = $request->input('is_show');
        $arr['cat_group'] = $request->input('cat_group');
        $arr['sort_order'] = $request->input('sort_order');
         //获取商品类型原本的图片路径
        $path = DB::table('goods_category')->where('id',$id)->value('image');
        $arr['image'] = $path;
        // dd($path);
        // dd($arr);
        //检测是否有文件上传
        if($request->hasFile('picname')) {
            //删掉商品类型原图
            if(file_exists($path)) {
                unlink($path);
            }
            //拼接文件夹路径
            $destinationPath = './admin/upload/'.date('Y-m-d').'/';
            //拼接文件路径
            $fileName = time().rand(100000, 999999);
            //获取上传文件的后缀
            $suffix = $request->file('picname')->getClientOriginalExtension();
            //文件的完整的名称
            $fullName = $fileName.'.'.$suffix;
            //保存文件
            $request->file('picname')->move($destinationPath, $fullName);
            //拼接文件上传之后的路径  
            $arr['image'] = $destinationPath.$fullName;
           
        }
        //更新商品类型信息
        $num = DB::table('goods_category')->where('id',$id)->update([
                'name'=>$arr['name'],
                'mobile_name'=>$arr['mobile_name'],
                'parent_id'=>$arr['parent_id'],
                'parent_id_path'=>$arr['parent_id_path'],
                'level'=>$arr['level'],
                'is_show'=>$arr['is_show'],
                'cat_group'=>$arr['cat_group'],
                'sort_order'=>$arr['sort_order'],
                'image'=>$arr['image'],
            ]);
        // dd($num);
        if($num) {
            return redirect(url('admin/Goods/categoryList'))->with('info','修改成功');
        }else{
            return back()->with('info','修改失败');
        }
    }


    /**
     * 通过商品的父级分类获得该分类下的所有子分类
     *
     * @param  int  $id
     * @param  collection  $value
     * @return \Illuminate\Http\Response
     */
    public function getCatTree($id, $value)
    {
       // dd($value);
        //声明两个全局变量
        global  $cat_list1, $cat_list;
        //先将该商品类型保存在另一个数组，然后在查询该类型的子类型
         // dd($cat_list1);
        $cat_list[$id] = $value;
        //查询子类型
        foreach ($cat_list1 as $key => $value){
             if($value->parent_id == $id)
             {  
                //通过回调查询所有的子类型   
                $this->getCatTree($value->id, $value);  
                $cat_list[$id]->have_son = 1; // 还有下级
             }
        }             
    }

    /**
     * 根据商品的上级类型查出所有的下级类型并返回给模板
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCategory(Request $request){
        $html = '';
        //获取ajax传过来的要查询的商品类型ID
        $parent_id = $request->input('value');
        //查询所有的下级类型并转成数组
        $cate = DB::table('goods_category')->where('parent_id', $parent_id)->get()->toArray();
        //遍历查询到的商品类型，并生成HTML
        foreach($cate as $k => $v){
            $html .= "<option value='{$v->id}'>{$v->name}</option>"; 
        }
        //将HTML返回给你模板
        return json_encode($html);
    } 

     /**
     * 删除商品分类信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del ($id)
    {
        //查询父ID为要删除的类型ID，检查该类型是否还有子类
        $res = DB::table('goods_category')->where('parent_id', $id)->get();
        //检验该类型下是否还有商品
        $goods = DB::table('goods')->where('cat_id', $id)->get();
        if(count($res)==true){
            //如果该商品类型还有子类型，就不能删除
            return back()->with('info', '删除失败,该商品类型还有子类型');
        }elseif(count($goods)==true){
        	//删除失败，该类型下还有商品
        	return back()->with('info', '删除失败,该商品类型还有商品');
        }else{
            //如果该商品类型没有子类型，则可以删除
            $path = DB::table('goods_category')->where('id',$id)->value('image');
            $bool = DB::table('goods_category')->where('id', $id)->delete();
            if($bool){
                if(file_exists($path)){
                    unlink($path);
                }
                return redirect(url('admin/Goods/categoryList'))->with('info','删除成功');
            }else{
                return back()->with('info', '删除失败');
            }
        }
    }

    /**
     * ajax异步更新状态
     *
     * @param Request $request
     * @return array
     */
    public function changeStatus(Request $request)
    {
        $input = $request->except('_token');
//        dd($input);
        //修改商品分类是否推荐的状态
        if(isset($input['is_hot']) && $input['is_hot']==1){
            $data = ['is_hot' => 0];
            $res = DB::table('goods_category')->where('id', $input['id'])->update($data);
        }elseif(isset($input['is_hot']) && $input['is_hot']==0){
            $data = ['is_hot' => 1];
            $res = DB::table('goods_category')->where('id', $input['id'])->update($data);
        }
        //修改商品分类是否显示的状态
        if(isset($input['is_show']) && $input['is_show']==1){
            $data = ['is_show' => 0];
            $res = DB::table('goods_category')->where('id', $input['id'])->update($data);
        }elseif(isset($input['is_show']) && $input['is_show']==0){
            $data = ['is_show' => 1];
            $res = DB::table('goods_category')->where('id', $input['id'])->update($data);
        }
        if(!empty($res)){
            $data = [
                'status' => 1,
                'msg' => '更新成功'
            ];
            return $data;
        }else{
            $data = [
                'status' => 0,
                'msg' => '更新失败'
            ];
            return $data;
        }
    }
}
