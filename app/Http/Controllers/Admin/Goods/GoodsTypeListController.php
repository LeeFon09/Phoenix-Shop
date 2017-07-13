<?php

namespace App\Http\Controllers\Admin\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\GoodsType;
use App\Libs\Pages\Page;

class GoodsTypeListController extends Controller
{
    /**
     * 显示所有的商品类型
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //查询商品类型的总条数
        $totalRows = count(DB::table('goods_type')->get());
        //创建一个分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Goods/goodsTypeList');
        //获取商品的全部类型
        $goodsType = GoodsType::orderBy('id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        //将查询到的商品类型返回给模板
        return view('Admin.Goods.goodsTypeList', compact('goodsType', 'pageObj'));
    }

    /**
     * 显示创建一个商品类型的表格
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.Goods.addGoodsType');
    }

    /**
     * 保存新增的商品类型信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //验证添加的表单信息
        $this->validate($request, [
            'name' => 'required|unique:goods_type',
        ],
        [
            'name.required' => '类型名称不能为空',
            'name.unique' => '已经存在该类型',
        ]);
        //获取新添加的商品类型名
        $name = $request->name;
        //将新添加的商品类型存到数据库
        $bool = DB::table('goods_type')->insert(['name'=>$name]);
        if($bool){
            return redirect(url('admin/Goods/goodsTypeList'))->with('info', '添加成功');
        }else{
            return back()->with('info', '添加失败');
        }
    }

    /**
     *显示修改商品类型的表格
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $goodsType = DB::table('goods_type')->where('id', $id)->get();
        return view('Admin/Goods/editGoodsType', compact('goodsType'));
    }

    /**
     * 更新商品类型的信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         //验证添加的表单信息
        $this->validate($request, [
            'name' => 'required|unique:goods_type',
        ],
        [
            'name.required' => '类型名称不能为空',
            'name.unique' => '已经存在该类型',
        ]);
       
        //获取更改后的商品类型名
        $name = $request->name;
        //更新数据库
        $bool = DB::table('goods_type')->where('id', $id)->update(['name'=>$name]);
        if($bool){
            return redirect(url('admin/Goods/goodsTypeList'))->with('info', '修改成功');
        }else{
            return back()->with('info', '修改失败');
        }
    }

    /**
     * 删除商品类型信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del($id)
    {
        //查询该类型下是否还有商品
        $goods = DB::table('goods')->where('goods_type', $id)->get();
        //查询该类型下是否还有规格
        $spec = DB::table('spec')->where('type_id', $id)->get();
        if(count($goods)==true || count($spec)==true){
            //该类型下还有商品或者规格，不能删除
            return back()->with('info', '删除失败，该类型下还有商品或者规格');
        }else{
            //该类型没有商品和规格，则可以删除
            $bool = DB::table('goods_type')->where('id', $id)->delete();
            if($bool){
                return redirect(url('admin/Goods/goodsTypeList'))->with('info', '删除成功');
            }else{
                return back()->with('info', '删除失败');
            }
        } 
    }
}
