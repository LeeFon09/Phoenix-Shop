<?php

namespace App\Http\Controllers\Admin\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\Libs\Pages\Page;

class GoodsAttributeListController extends Controller
{
    /**
     * 显示所有的商品属性信息
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //查询商品属性的总条数
        $totalRows = count(DB::table('goods_attribute')->get());
        //创建一个分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Goods/goodsAttributeList',10,'p',['type_id'=>$request->type_id]);
        //查询所有的商品类型
        $type = DB::table('goods_type')->get();
        //查询所有的商品属性并倒序排序
        $attr = DB::table('goods_attribute')->where(function($query) use($request, &$totalRows, &$pageObj) {
            if(!empty($request->type_id)){
                $query->where('type_id', $request->type_id);
                $totalRows = count(DB::table('goods_attribute')->where('type_id', $request->type_id)->get());
                $pageObj = new Page($totalRows, $request, 'admin/Goods/goodsAttributeList',10,'p',['type_id'=>$request->type_id]);
            }
        })->orderBy('attr_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        foreach($attr as $k => $v){
            //查询每个商品属性对应的商品类型名称
            $attr[$k]->type = DB::table('goods_type')->where('id', $v->type_id)->value('name');
        }
        return view('Admin.Goods.goodsAttributeList', compact('attr','type', 'pageObj'));
    }

    /**
     * 显示创建一个新的商品属性表格
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $goodsType = DB::table('goods_type')->get();
        return view('Admin.Goods.addGoodsAttribute', compact('goodsType'));
    }

    /**
     * 保存新的商品属性信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //验证添加的表单信息
        $this->validate($request, [
            'attr_name' => 'required|unique:goods_attribute',
            'type_id' => 'required',
            'attr_index' => 'required',
            'attr_input_type' => 'required',
        ],
        [
            'attr_name.required' => '属性名称不能为空',
            'attr_name.unique' => '已经存在该属性',
            'type_id.required' => '请选择所属商品类型',
            'attr_index.required' => '请选择检索方式',
            'attr_input_type.required' => '请选择属性值的录入方式',
        ]);
        if(!empty($request->attr_values)){
            $value = $request->attr_values;
        }else{
            $value = '';
        }
        $bool = DB::table('goods_attribute')->insert([
                'attr_name' => $request->attr_name,
                'type_id' => $request->type_id,
                'attr_index' => $request->attr_index,
                'attr_input_type' => $request->attr_input_type,
                'attr_values' => $value,
            ]);
        if($bool){
            return redirect(url('admin/Goods/goodsAttributeList'))->with('info', '添加成功');
        }else{
            return back()->with('info', '添加失败');
        }
    }

    /**
     * 显示修改商品属性的表格
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $goodsType = DB::table('goods_type')->get();
        $attr = DB::table('goods_attribute')->where('attr_id', $id)->get();
        return view('Admin/Goods/editGoodsAttribute', compact('attr', 'goodsType'));
    }

    /**
     * 更新商品属性信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //验证添加的表单信息
        $this->validate($request, [
            'attr_name' => 'required|unique:goods_attribute',
            'type_id' => 'required',
            'attr_index' => 'required',
            'attr_input_type' => 'required',
        ],
        [
            'attr_name.required' => '属性名称不能为空',
            'attr_name.unique' => '已经存在该属性',
            'type_id.required' => '请选择所属商品类型',
            'attr_index.required' => '请选择检索方式',
            'attr_input_type.required' => '请选择属性值的录入方式',
        ]);
        if(!empty($request->attr_values)){
            $value = $request->attr_values;
        }else{
            $value = '';
        }
        $bool = DB::table('goods_attribute')->where('attr_id', $id)->update([
                'attr_name' => $request->attr_name,
                'type_id' => $request->type_id,
                'attr_index' => $request->attr_index,
                'attr_input_type' => $request->attr_input_type,
                'attr_values' => $value,
            ]);
        if($bool){
            return redirect(url('admin/Goods/goodsAttributeList'))->with('info', '修改成功');
        }else{
            return back()->with('info', '修改失败');
        }
    }

    /**
     * 查找商品属性信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search (Request $request, $id='')
    {
        //查询所有的商品类型
        $type = DB::table('goods_type')->get();
        //根据商品类型ID查询该商品类型属性并倒序排序
       if(!empty($id)){
            //查询总条数
            $totalRows = count(DB::table('goods_attribute')->where('type_id', $id)->get());
            //创建一个分页对象
            $pageObj = new Page($totalRows, $request, 'admin/Goods/goodsAttributeList',10,'p',['type_id'=>$id]);
            $attr = DB::table('goods_attribute')->where('type_id', $id)->orderBy('attr_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
         }else{
            //查询总条数
            $totalRows = count(DB::table('goods_attribute')->get());
            //创建一个分页对象
            $pageObj = new Page($totalRows, $request, 'admin/Goods/goodsAttributeList');
            $attr = DB::table('goods_attribute')->orderBy('attr_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
         }  
        //查询该商品类型的属性对应的商品类型名称
        foreach($attr as $k => $v){
            $attr[$k]->type = DB::table('goods_type')->where('id', $v->type_id)->value('name');
        }
        return view('Admin.Goods.goodsAttributeList', compact('attr','type', 'pageObj'));
    }

    /**
     * 删除商品属性信息
     *
     * @param  int  $id
     */
    public function del($id)
    {
        //查询是否还有商品含有该属性
        $res = DB::table('goods_attr')->where('attr_id', $id)->get();
         if(count($res)==true){
            //还有商品含有该属性，不能删除
            return back()->with('info', '删除失败，还有商品含有该属性');
        }else{
            //没有商品含有该属性，可以删除
            $bool = DB::table('goods_attribute')->where('attr_id', $id)->delete();
            if($bool){
                return redirect(url('admin/Goods/goodsAttributeList'))->with('info', '删除成功');
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
        //修改商品属性是否筛选的状态
        if(isset($input['attr_id']) && $input['attr_index']==1){
            $data = ['attr_index' => 0];
            $res = DB::table('goods_attribute')->where('attr_id', $input['attr_id'])->update($data);
        }elseif(isset($input['attr_id']) && $input['attr_index']==0){
            $data = ['attr_index' => 1];
            $res = DB::table('goods_attribute')->where('attr_id', $input['attr_id'])->update($data);
        }
        if($res){
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
