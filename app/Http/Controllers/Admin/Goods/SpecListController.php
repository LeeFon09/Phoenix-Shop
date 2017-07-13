<?php

namespace App\Http\Controllers\Admin\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\Libs\Pages\Page;

class SpecListController extends Controller
{
    /**
     * 显示所有的商品规格信息
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //查询商品规格的总条数
        $totalRows = count(DB::table('spec')->get());
        //创建一个分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Goods/specList');
        //获取所有商品类型
        $type = DB::table('goods_type')->get();
        //获取所有的商品规格类型并倒序排序
        $spec = DB::table('spec')->where(function($query) use($request, &$totalRows, &$pageObj) {
            if(!empty($request->type_id)){
                $query->where('type_id', $request->type_id);
                $totalRows = count(DB::table('goods_attribute')->where('type_id', $request->type_id)->get());
                $pageObj = new Page($totalRows, $request, 'admin/Goods/goodsAttributeList',10,'p',['type_id'=>$request->type_id]);
            }
        })->orderBy('id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        foreach ($spec as $k => $v) {
            //获取每个规格对应的商品类型
            $spec[$k]->type = DB::table('goods_type')->where('id', $v->type_id)->value('name');
        }
        foreach($spec as $k2 => $v2){
            //获取每个规格的规格项
            $spec[$k2]->item= DB::table('spec_item')->select('item')->where('spec_id', $v2->id)->get();
        }
        return view('Admin.Goods.specList', compact('spec', 'type', 'pageObj'));
    }

    /**
     * 显示添加商品规格的表格
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //查询所有的商品类型，以便选择规格所属商品类型
        $type = DB::table('goods_type')->get();
        return view('Admin.Goods.addSpec', compact('type'));
    }

    /**
     * 保存商品规格的信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //验证添加的表单信息
        $this->validate($request, [
            'name' => 'required|unique:spec',
            'type_id' => 'required',
            'search_index' => 'required',
            'items' => 'required',
            'order' => 'numeric'
        ],
        [
            'name.required' => '规格名称不能为空',
            'name.unique' => '已经存在规格',
            'type_id.required' => '请选择所属商品分类',
            'search_index.required' => '请选择商品能否检索',
            'items.required' => '至少填写一个规格项',   
            'order.numeric' => '排序必须为数字',
         ]);
        //判断是否有排序这个字段的值，没有就给一个默认值50
        $order = empty($request->order)?50:$request->order;
        //开始一个事务
        DB::beginTransaction();
        //将新添加的规格信息插到商品规格表中
        $bool = DB::table('spec')->insert([
                'name' => $request->name,
                'type_id' => $request->type_id,
                'search_index' => $request->search_index,
                'name' => $request->name,
                'order' => $order,
            ]);
        //将规格项分成数组,以便插入规格项表
        $item = explode(',',$request->items);
        //查询新添加的规格ID
        $spec_id = DB::table('spec')->where('name', $request->name)->value('id');
        //遍历存储规格项的数组，分别插入规格项表
        foreach($item as $v){
            $bool2 = DB::table('spec_item')->insert([
                    'spec_id' => $spec_id,
                    'item' => $v,
                ]);
            if($bool2 == false){
                break;
            }
        }
        //判断所有的数据库操作是否都正确
        if($bool == true && $bool2 == true){
            //所有的数据库操作都正确，提交事务
            DB::commit();
            return redirect(url('admin/Goods/specList'))->with('info', '添加成功');
        }else{
            //数据库操作有误，执行回滚事务
            DB::rollBack();
            return back()->with('info', '添加失败');
        }
    }

    /**
     * 显示修改商品规格的表格
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //查询所有的商品类型，以便选择规格所属商品类型
        $type = DB::table('goods_type')->get();
        //根据规格ID查询相应的规格
        $spec = DB::table('spec')->where('id', $id)->get();
        //查询该规格的规格项
        $items = DB::table('spec_item')->where('spec_id', $id)->pluck('item')->toArray();
        $items = implode(',',$items);
        return view('Admin.Goods.editSpec', compact('type', 'spec', 'items'));
    }

    /**
     * 更新商品规格的信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //验证添加的表单信息
        $this->validate($request, [
            'name' => 'required',
            'type_id' => 'required',
            'search_index' => 'required',
            'items' => 'required',
            'order' => 'numeric'
        ],
        [
            'name.required' => '规格名称不能为空',
            'type_id.required' => '请选择所属商品分类',
            'search_index.required' => '请选择商品能否检索',
            'items.required' => '至少填写一个规格项', 
            'order.numeric' => '排序必须为数字',
         ]);
        //判断是否有排序这个字段的值，没有就给一个默认值50
        $order = empty($request->order)?50:$request->order;
        //开启事务
        DB::beginTransaction();
        //更新规格表
        $bool = DB::table('spec')->where('id', $id)->update([
                'name' => $request->name,
                'type_id' => $request->type_id,
                'search_index' => $request->search_index,
                'name' => $request->name,
                'order' => $order,
            ]);

        //更新规格项表之前先删除原始信息，避免重复或多余
        $bool2 = DB::table('spec_item')->where('spec_id', $id)->delete();
        $item = explode(',',$request->items);
        $spec_id = DB::table('spec')->where('name', $request->name)->value('id');
        foreach($item as $v){
            $bool3 = DB::table('spec_item')->insert([
                    'spec_id' => $spec_id,
                    'item' => $v,
                ]);
            if($bool3 == false){
                break;
            }
        }
        //判断数据库操作是否都正确
        if($bool ==true && $bool2 == true && $bool3 == true){
            //数据库操作都正确。提交事务
            DB::commit();
            return redirect(url('admin/Goods/specList'))->with('info', '修改成功');
        }else{
            //数据库操作有误，执行事务回滚
            DB::rollBack();
            return back()->with('info', '修改失败');
        }
    }

    /**
     * 根据条件查找商品规格信息
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function search (Request $request)
    {
        
        //获取所有的商品类型
        $type = DB::table('goods_type')->get();
        //判断传过来的类型ID是否为空
        if(!empty($request->type_id)){
            //查询总条数
            $totalRows = count(DB::table('spec')->where('type_id', $request->type_id)->get());
            //创建一个分页对象
            $pageObj = new Page($totalRows, $request, 'admin/Goods/specList',10,'p',['type_id'=>$request->type_id]);
            //根据传来的商品类型ID查询该类型对应的商品规格
             $spec = DB::table('spec')->where('type_id', $request->type_id)->orderBy('id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        }else{
            //查询总条数
            $totalRows = count(DB::table('spec')->get());
            //创建一个分页对象
            $pageObj = new Page($totalRows, $request, 'admin/Goods/specList');
            //传过来的ID为空就查询所有
             $spec = DB::table('spec')->orderBy('id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        }
       
        foreach ($spec as $k => $v) {
            //获取该规格对应的商品类型
            $spec[$k]->type = DB::table('goods_type')->where('id', $v->type_id)->value('name');
        }
        foreach($spec as $k2 => $v2){
            //获取该规格的规格项
            $spec[$k2]->item= DB::table('spec_item')->select('item')->where('spec_id', $v2->id)->get();
        }
        return view('Admin.Goods.specList', compact('spec', 'type', 'pageObj'));
    }


    /**
     * 删除商品规格信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del($id)
    {
        //查询商品价格库存表中所有的商品规格项的ID，以判断能否删除该规格
        $key = DB::table('spec_goods_price')->pluck('key');
        //查询要删除的规格中的所有规格项ID
        $item_id = DB::table('spec_item')->where('spec_id', $id)->pluck('id');
        //对得到的两个规格项ID进行比较，判断商品价格库存表中是否存在要删除的规格
        foreach($key as $v){
            foreach($item_id as $v2){
                $tmp = explode('_', $v);
                $bool = in_array($v2, $tmp);
                if($bool==true){
                    //商品价格库存表中存在要删除的规格，则跳出循环
                    break 2;
                }
            }
        }
        if($bool==false){
            //商品价格库存表中不存在要删除的规格,所以可以删除
            //开启事务
            DB::beginTransaction();
            //先删除该规格
            $bool2 = DB::table('spec')->where('id', $id)->delete();
            //删除该规格中的规格项
            $bool3 = DB::table('spec_item')->where('spec_id', $id)->delete();
            //判断数据库操作是否都正确
            if($bool2 == true && $bool3 == true){
                //数据库操作都正确。提交事务
                DB::commit();
                 return redirect(url('admin/Goods/specList'))->with('info', '删除成功');
            }else{
                //数据库操作有误，执行事务回滚
                DB::rollBack();
                return back()->with('info', '删除失败');
            }
        }else{
            return back()->with('info', '删除失败，该规格下还有商品');
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
        //修改商品评论是否显示的状态
        if(isset($input['id']) && $input['search_index']==1){
            $data = ['search_index' => 0];
            $res = DB::table('spec')->where('id', $input['id'])->update($data);
        }elseif(isset($input['id']) && $input['search_index']==0){
            $data = ['search_index' => 1];
            $res = DB::table('spec')->where('id', $input['id'])->update($data);
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
