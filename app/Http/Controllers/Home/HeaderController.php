<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use hightman\xunsearch\lib;

class HeaderController extends Controller
{

    public function header()
    {
        //select * from __PREFIX__ad where position_id = 1 limit 1
        $ad = DB::table("ad")->where("pid",1)->limit(1)->first();
//        dd($ad);
        return view("Home.Public.header");

    }

    public function showArticle($id)
    {
        return view('Home.Article.article');
    }


    /**
     * 头部搜索
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function search()
    {
        $q = Input::get("q");
        if(empty($q)){
            return redirect("home/goods/goodslist/2");
        }

        //开始xunsearch
        $xs = new \XS("goods");
        $search = $xs->search;
        $res= $search->search($q);
        if(empty($res)){
            return redirect("home/goods/goodslist/2");
        }

        $goods_id = $res[0]->getFields("goods_id")["goods_id"];

        //根据商品id，获得goods_category表的id
       $category_id = DB::table("goods")->join("goods_category", "goods_category.id", "=", "goods.cat_id")->
            select("goods_category.id")->where("goods_id",$goods_id)->first();
//       dd($category_id->id);
       return redirect("home/goods/goodslist/".$category_id->id);
    }
}
