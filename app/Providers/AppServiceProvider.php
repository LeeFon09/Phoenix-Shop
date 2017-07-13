<?php

namespace App\Providers;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //公共页头视图
        View::composer("Home.Public.header", function($view){
            //首页最顶部大块广告
            $ad = DB::table("ad")->where("pid",1)->limit(1)->first();
            //导航栏
            $nav = DB::table("navigation")->where("is_show", 1)->orderBy("sort","desc")->get();
            //热门搜索
            $search = DB::table("config")->where("name", "hot_keywords")->value("value");
            $search =explode("|", $search);
            //购物车商品数量
            $car_num = count(session("cart"));

            $view->with(['ad' => $ad, "nav" => $nav, "search" => $search, "car_num" => $car_num]);
        });

        //公共登录会员昵称显示
        View::composer("Home.Public.siteTopbar", function($view){
              if(!session("user")){
                 $nickname = "会员".mt_rand(1111,9999);
              }else{
                  $nickname = session("user")->nickname;
              }

            if(!$nickname){
                $nickname = session("user")->email;
            }

            if(!$nickname){
                $nickname = session("user")->mobile;
            }

            $view->with(["nickname" => $nickname]);
        });

        //全部商品分类
        View::composer("Home.Public.header", function($view){
            $das = DB::table("goods_category")->offset(1)->limit(6)->where("parent_id", 0)->get();
            //第一级分类
            if(!Cache::store("file")->has("allShopType")){
                $parent = $das;
                foreach($das as $k=>$v){
                    $this->getTree($v->id);
                    $parent[$k]->menus = $this->arr;
                    foreach($parent[$k]->menus as $ko=>$vo){
                        $this->getTree($vo->id);
                        $parent[$k]->menus[$ko]->child = $this->arr;
                    }
                }
                Cache::store("file")->put("allShopType", $parent, 1440);
            }

            $parents = Cache::store("file")->get("allShopType");

            $view->with("res", $parents);
        });


        //公共页脚视图：
        View::composer("Home.Public.footer", function($view){
            $subIds = DB::table('article')->join('article_cat','article_cat.cat_id','=','article.cat_id')
                ->whereIn('article_cat.cat_id',[1,2,3,4,7])->get();
            $artIds = array();
            foreach($subIds as $obj){
                if(!in_array($obj->cat_name,$artIds)){
                    $artIds[$obj->cat_id] = $obj->cat_name;
                }
            }
            $view->with(['artIds' => $artIds,'subIds' => $subIds]);
        });


        //处理后台界面右下角的新订单通知
        View::composer('Admin.Public.footer', function ($view) {
            $arr = [
                'handle_status' => 2,
                'order_status' => 3
            ];
            $orderAmount = DB::table('order')->where($arr)->count();//待处理订单
            $view->with(['orderAmount' => $orderAmount]);
        });

    }

    /**
     * 获得子分类
     * @param int $pid
     */
    public function getTree($pid=0)
    {
        $this->arr = [];
        $data = DB::table("goods_category")->where("parent_id", $pid)->get();
        foreach($data as $v){
            if($v->parent_id == $pid){
                $this->arr[] = $v;
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
