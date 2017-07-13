<?php

namespace App\Http\Controllers\Home;

use App\Model\Goods;
use App\Model\GoodsCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * 首页
 * Class IndexController
 * @package App\Http\Controllers\Home
 */
class IndexController extends Controller
{
    public $arr =[];

    /**
     * 首页
     *
     *
     */
    public function index()
    {

        return view('Home.Index.index');

    }

    /**
     * 轮播图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function banner()
    {

        //<!---广告 select * from __PREFIX__ad where position_id = 2 limit 1-->
        $slideshow = DB::table("ad")->where("pid", 2)->limit(2)->get();

        //标题
        $title = DB::table("config")->where("name", "store_title")->value("value");

        //热门商品
        $hotgoods = $this->hotGoods();

        //首页右侧公告上方广告位
        $topAd = $this->topAd();

        //首页右侧公告下方广告位
        $underAd = $this->underAd();

        //最底部广告位
        $footAd = $this->footAd();

        //右侧公告
        $notice = $this->notice();

        //右侧新闻
        $news = $this->news();



        //首页中间部分广告
        $middleAd = $this->middleAd();

        //中间商品大分类
//        $parent = $this->shopParent();

        //中间商品小分类
//        $child = $this->shopChild();
//
//        //中间大块热卖商品遍历
//        $list = $this->shopList();
//
//        //中间大块六个商品
//        $lists = $this->shopLists();


        return view("Home.Index.index", compact("title" , "slideshow", "hotgoods", "topAd", "footAd", "notice",
                "news", "underAd", "middleAd")
        );
    }

    /**
     * 热卖商品
     * @return mixed 热买商品
     */
    public function hotGoods()
    {
        if(!Redis::get('hotGoods')){
            $hotgoods = DB::table("goods")->where([
                ["is_new", "=", 1],
                ["is_hot", "=", 1],
                ["is_recommend", "=", 1],
                ["is_on_sale", "=", 1]
            ])->orderBy("goods_id", "desc")->limit(4)->get();
            Redis::set('hotGoods', json_encode($hotgoods));
        }
        $hotgoods = json_decode(Redis::get('hotGoods'));
        return $hotgoods;

    }

    /**
     * 首页右侧公告上方广告位
     * @return mixed
     */
    public function topAd()
    {
        $topAd = DB::table("ad")->where("pid", 7)->limit(1)->get();
        return $topAd;
    }


    /**
     * 首页右侧公告下方广告位
     * @return mixed
     */
    public function underAd()
    {
        $underAd = DB::table("ad")->where("pid", 8)->limit(1)->get();
        return $underAd;
    }


    /**
     * 首页右侧公告
     * @return mixed
     */
    public function notice()
    {
        $notice = DB::table("article")->where("cat_id",5)->orderBy("article_id","desc")->limit(4)->get();
        return $notice;
    }


    /**
     * 首页右侧新闻
     * @return mixed
     */
    public function news()
    {
        $news = DB::table("article")->where("cat_id", 6)->orderBy("article_id", "desc")->limit(4)->get();
        return $news;
    }


    /**
     * 首页中间部分广告
     * @return mixed
     */
    public function middleAd()
    {
        $middleAd = DB::table("ad")->where("pid", 3)->limit(1)->get();
        return $middleAd;
    }


    /**
     * 首页底部广告
     * @return mixed
     */
    public function footAd()
    {
        $footAd = DB::table("ad")->where("pid", 4)->limit(1)->get();
        return $footAd;
    }


    /**
     * 中间商品大分类
     * @return mixed
     */
    public function shopParent()
    {
        //select* from `__PREFIX__goods_category` where is_show = 1 and `level` = 1  limit 7
        if(!Redis::get('shopParent')) {
            $parent = DB::table("goods_category")->select("id", "name")->where(["is_show" => 1, "level" => 1])->limit(7)->get();
            Redis::set('shopParent', json_encode($parent));
        }
        $parent = json_decode(Redis::get('shopParent'));


        return $parent;
    }


    /**
     * 中间商品小分类
     * @return mixed
     */
    public function shopChild()
    {
        if(!Redis::get('shopChild')) {
            $parent = $this->shopParent();
            foreach ($parent as $k => $v) {
                $id = $v->id;
                $child[$k] = DB::table("goods_category")->select("name")->where(["is_show" => 1, "parent_id" => $id])->get()->all();
            }
            Redis::set('shopChild', json_encode($child));
        }
        $child = json_decode(Redis::get('shopChild'));
        $parent = $this->shopParent(); //商品大分类
        $list = $this->shopList(); //大块热卖商品
        $lists = $this->shopLists(); //六块小商品

        return view("Home.Index.hot", compact("parent", "child", "list", "lists"));
//        return $child;
    }


    /**
     * 中间大块热卖商品
     * @return array
     */
    public function shopList()
    {
        //select * from `__PREFIX__goods` where cat_id in($cat_id_str) and is_on_sale = 1 order by goods_id limit 1
        if(!Redis::get('shopList')) {
            $ls = $this->parentId();
            $parent = $this->shopParent();
            foreach ($parent as $v) {
                $list[][$v->id] = DB::table("goods")->whereIn("cat_id", $ls[$v->id])
                    ->where("is_on_sale", 1)->orderBy("goods_id")->limit(7)->get();
            }
            Redis::set('shopList', json_encode($list));
        }
        $list = json_decode(Redis::get('shopList'));
        return $list;

    }


    /**
     * 中间大块六个商品
     * @return array
     */
    public function shopLists()
    {
        //select * from `__PREFIX__goods` where  cat_id in($cat_id_str) and is_on_sale = 1 order by goods_id limit 1,6
        if(!Redis::get('shopLists')) {
            $ls = $this->parentId();
            $parent = $this->shopParent();
            foreach ($parent as $v) {
                $lists[][$v->id] = DB::table("goods")->whereIn("cat_id", $ls[$v->id])
                    ->where("is_on_sale", 1)->offset(1)->limit(6)->orderBy("goods_id")->get()->all();
            }
            Redis::set('shopLists', json_encode($lists));
        }
        $lists = json_decode(Redis::get('shopLists'));
        return $lists;
    }

    /**
     * 获得某个大分类的所有子分类
     * @return mixed
     */
    public function parentId()
    {
        $parent = $this->shopParent();
        $data = DB::table("goods_category")->select("id", "parent_id")->get()->all();
        foreach($parent as $v){
            $this->arr = [];
            $this->getTree($data,$v->id);
            $ls[$v->id] = $this->arr;
        }
        return $ls;
    }


    /**
     * 以父类id来获得所有子分类的id
     * @param $data 需要分类的数据
     * @param int $pid 父类id
     */
    public function getTree($data, $pid=0)
    {
        foreach($data as $v){
            if($v->parent_id == $pid){
                $this->arr[] = $v->id;
                $this->getTree($data,$v->id);
            }
        }
    }







}