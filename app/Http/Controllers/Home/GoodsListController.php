<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Libs\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GoodsListController extends Controller
{
    /**
     * 商品列表页
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function goodsList(Request $request,$id)
    {
        //当前商品分类
        $currCate = DB::table('goods_category')->where('id', $id)->first();
        //左侧商品分类列表
        $cateArr = $this->getGoodsCate($currCate);
        $where = null;
        switch ($currCate->level) {
            case '1':
                $where = ['cat_level_1'=>$id];
                break;
            case '2':
                $where = ['cat_level_2'=>$id];
                break;
            case '3':
                $where = ['cat_id'=>$id];
                break;
            default:
                abort(404,'选择的分类未找到!');
                break;
        }
        $count = DB::table('goods')->where($where)->count();

        $pageObj = new Page($count,$request,"home/goods/goodslist/{$id}");//分页类实例化
        //当前商品分类对应子类集合
        $goodsList = DB::table('goods')->select('goods_id', 'goods_name', 'shop_price', 'original_img', 'sales_sum')
            ->where($where)->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        //添加商品小图
        $this->getGoodsSmallImages($goodsList);
        //推荐热卖5条数据
        $recGoods = DB::table('goods')->where('is_recommend', 1)->limit(5)->get();

        return view('Home.Goods.goodsList', compact('currCate', 'cateArr', 'goodsList', 'recGoods','pageObj'));
    }

    private function getGoodsSmallImages(&$goodsList){
        foreach($goodsList as $obj){
            $obj->small_images = DB::table('goods_images')->where('goods_id',$obj->goods_id)->get();
        }

    }

    /**
     * 加入收藏
     * @param $goodsId
     * @return \Illuminate\Http\JsonResponse
     */
    public function goodsCollectAdd($goodsId)
    {
        $data = [
            "user_id" =>session('user')->user_id,
            "goods_id"=>$goodsId
        ];
        DB::table('goods_collect')->insert($data);
        return response()->json(["status"=>0,"msg"=>"加入收藏成功!"]);
    }


    /**
     * 传入当前分类 如果当前是 2级 找一级
     * 如果当前是 3级 找2 级 和 一级
     * @param  $currCate
     */
    public function getGoodsCate(&$currCate)
    {
        if (empty($currCate)) return array();
        $cateAll = $this->getGoodsCategoryTree();
        if ($currCate->level == 1) {
            $cateArr = $cateAll[$currCate->id]->first_menu;
            $currCate->top_name = $currCate->name;
            $currCate->open_id = $currCate->id;
            $currCate->select_id = 0;
        } elseif ($currCate->level == 2) {
            $cateArr = $cateAll[$currCate->parent_id]->first_menu;
            $currCate->top_name = $cateAll[$currCate->parent_id]->name;//顶级分类名称
            $currCate->open_id = $currCate->id;//默认展开分类
            $currCate->select_id = 0;
        } else {
            $parent = DB::table('goods_category')->where("id" , $currCate->parent_id)->orderBy('sort_order','desc')->first();//父类
            $cateArr = $cateAll[$parent->parent_id]->first_menu;
            $currCate->top_id = $parent->parent_id;
            $currCate->top_name = $cateAll[$parent->parent_id]->name;//顶级分类名称
            $currCate->parent_name = $parent->name;
            $currCate->open_id = $parent->id;
            $currCate->select_id = $currCate->id;//默认选中分类
        }
        return $cateArr;
    }

    /**
     * 获取商品一二三级分类
     * @return type
     */
    private function getGoodsCategoryTree(){
        $result = array();

        $cat_list = DB::table('goods_category')->where("is_show","1")->get();//所有分类
        //构造三级分类列表
        foreach ($cat_list as $obj){
            if($obj->level == 3){
                $thdArr[$obj->parent_id][] = $obj;
            }
            if($obj->level == 2){
                $secArr[$obj->parent_id][] = $obj;
            }
            if($obj->level == 1){
                $tree[] = $obj;
            }
        }
        if(count($secArr)>0){
            foreach ($secArr as $k=>$v){
                foreach ($v as $kk=>$vv){
                    $secArr[$k][$kk]->second_menu = empty($thdArr[$vv->id]) ? array() : $thdArr[$vv->id];
                }
            }
        }

        if(count($tree)>0){
            foreach ($tree as $obj){
                $obj->first_menu = empty($secArr[$obj->id]) ? array() : $secArr[$obj->id];
                $result[$obj->id] = $obj;
            }
        }
        return $result;
    }
}
