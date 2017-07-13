<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;


/**
 * 商品详情页
 * Class GoodsDetailController
 * @package App\Http\Controllers\Home
 */
class GoodsDetailController extends Controller
{

    protected $id;

    public function index($id)
    {
        $this->id = $id;
        //点击量自增
        DB::table("goods")->increment("click_count");

        //详情页类型链   首页 >> 家用电器 >> 大家电 >> 电视 >>
        $categoryLink = $this->categoryLink();
        if(!$categoryLink){
            return redirect('home/goods/goodsinfo/1');
        }

        //商品价格
        $goodsPrice = $this->goodsPrice();
//        dd($goodsPrice);

        //商品详细信息
        $goods = $this->goods();

        //商品详情下面的的图片单独处理
        $imgs = $this->imgs();

        //轮播小图片集合 并且其中带有放大镜
        $imageLists = $this->imageLists();

        //限时抢购
        $flashSale = $this->flashSale();

        //商品详情的规格类型
        $species = $this->species();

        //左侧商品推荐
        $recommend = $this->shopRecommend();

        //商品规格参数
        $goodsAttr = $this->goodsAttr();

        //商品评论个数
        $comment= $this->comment();
//        dd($comment);
        //好评度
        $rate = $this->perComment();

        //随着规格改变价格
//        $specGoodsPrice = $this->specGoodsPrice();
//        dd($specGoodsPrice);

        return view("Home.Goods.goodsInfo", compact("categoryLink", "goods",  "imgs",
                    "imageLists", "flashSale", "species", "recommend", "goodsAttr",
                    "comment", "rate", "userComment", "goodsPrice"));
    }


    /**
     * 详情页类型链    首页 >> 家用电器 >> 大家电 >> 电视 >>
     * @return array
     */
    public function categoryLink()
    {

        $path = DB::table("goods")
                ->select("goods_category.parent_id_path")
                ->join("goods_category", "goods.cat_id", "=", "goods_category.id")
                ->where("goods_id", $this->id)->first();
        if(!$path){
            return false;
        }
        //分割家族图谱，查找到所有的父类, 包括自己
        $path = ltrim($path->parent_id_path,"0_");
        $ex = explode("_", $path);
        foreach($ex as $k => $v){
            $name[$k] = DB::table("goods_category")->select("name", "id")->where("id", $v)->first();
        }
        return $name;
    }



    public function goodsPrice()
    {
        $goodsPrice = DB::table("spec_goods_price")->select("price")->where("goods_id", $this->id)->first();
        if(!$goodsPrice){
            $goodsPrice = DB::table("goods")->select("shop_price")->where("goods_id", $this->id)->first();
            $goodsPrice->price = $goodsPrice->shop_price;
        }
        return $goodsPrice;
    }


    /**
     * 商品详细信息
     * @return mixed
     */
    public function goods()
    {
        if(!isset($this->id)){
            $this->id = 1;
        }
        $goods = DB::table("goods")->where("goods_id", $this->id)->first();
        return $goods;
    }


    /**
     * 商品详情下面的的图片单独处理
     * @return mixed
     */
    public function imgs()
    {
        //<p><img src="/Public/upload/goods/2016/01-14/5697524715ff6.jpg" /></p>
        $imgs = htmlspecialchars_decode($this->goods()->goods_content);
        preg_match_all("/(\/Public)(.*?)(.jpg)/", $imgs,$res );
        return $res[0];
    }


    /**
     * 限时抢购
     * @return mixed
     */
    public function flashSale()
    {
        $flashSale = DB::table("flash_sale")->select("price", "description", "end_time")
                     ->where("goods_id", $this->id)->first();
        return $flashSale;
    }


    /**
     * 轮播小图片集合 并且其中带有放大镜
     * @return mixed
     */
    public function imageLists()
    {
        $goods_image_lists = DB::table("goods_images")->where("goods_id", $this->id)->get()->all();
        return $goods_image_lists;
    }


    /**
     * 商品详情规格类型
     * @return mixed
     */
    public  function  species()
    {
        $goodsPrice = DB::table("spec_goods_price")->where("goods_id", $this->id)->get()->all();
//        dd($goodsPrice);
        foreach($goodsPrice as $v){
            $key = explode("_", $v->key);
//            dd($key);
            foreach($key as $vo){
                $arr[] = $vo;
            }
        }
        if(!empty($arr)){
            $arrs = array_unique($arr);
            foreach($arrs as $vo){
                $item = DB::table("spec_item")->where("id", $vo)->get()->all();
                foreach($item as $vv){
                    $spec = DB::table("spec")->where("id", $vv->spec_id)->get()->all();
                    $arr2[] = $spec;
                    foreach($spec as $vk){
                        $arr3[$vk->name][]=$vv;
                    }
                }
            }
        }else{
            $arr3 = [];
        }

        return $arr3;
    }

    /**
     * 左侧商品推荐
     * @return mixed
     */
    public function shopRecommend()
    {
      //select * from `__PREFIX__goods` where is_recommend = 1 order by goods_id desc limit 10
        $recommend= DB::table("goods")->where("is_recommend", 1)->orderBy("goods_id", "desc")->limit(10)->get();
        return $recommend;
    }

    /**
     * 商品规格参数
     * @return mixed
     */
    public function goodsAttr()
    {
        $goodsAttr = DB::table("goods_attr as a")
            ->join("goods_attribute as b", "a.attr_id", "=", "b.attr_id")
            ->select("b.attr_name", "a.attr_value")
            ->where("goods_id", $this->id)->get();
        return $goodsAttr;
    }

    /**
     * 商品评论个数
     * 物流评价+商品评价+商家服务评价 1-4之间为差评
     * 物流评价+商品评价+商家服务评价 5-9之间为中评
     * 物流评价+商品评价+商家服务评价 10-15为好评
     * @return arr
     *
     */
    public function comment()
    {
        $comment = DB::table("comment")->select("deliver_rank", "goods_rank", "service_rank")
                            ->where(["goods_id" => $this->id,"is_show" => 1])
                            ->get();
//        return $comment;
        foreach ($comment as $v){
            $comments[] = $v->deliver_rank + $v->goods_rank + $v->service_rank;

        }
        if(!isset($comments)){
            $comments = [];
        }
        $c1 = range(1, 4);
        $c2 = range(5, 9);
        $c3 = range(10, 15);
        $n1 = 0; //差评个数
        $n2 = 0;//中评个数
        $n3 = 0;//好评个数

        foreach($comments as $v){
            if(in_array($v, $c1)){
                $n1 += 1;
            }
            if(in_array($v, $c2)){
                $n2 += 1;
            }
            if(in_array($v, $c3)){
                $n3 += 1;
            }
        }

        $comment = ["n1"=>$n1, "n2"=>$n2, "n3"=>$n3];


        return $comment;
    }

    /**
     * 商品评论
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userComment($id)
    {
        $commentType = Input::get("commentType"); // 2好评  3中评  4差评
        if($commentType == 1){
            $comment = DB::table("comment")->where(["goods_id" => $id, "is_show" => 1, "parent_id" => 0 ])->orderBy("comment_id","desc")->get();
        }else{
            $typeArr = array(2=>[4,5],3=>[3],4=>[0,1,2]);
            $comments = DB::table("comment")->where(["goods_id" => $id, "is_show" => 1, "parent_id" => 0 ])->orderBy("comment_id","desc")->get();
            foreach($comments as $v){
                $deliver_rank = $v->deliver_rank; //物流评价等级
                $goods_rank = $v->goods_rank; //商品评价等级
                $service_rank = $v->service_rank; //商家服务态度评 价等级

                $sum = ceil(($deliver_rank + $goods_rank + $service_rank)/3);
                if(in_array($sum, $typeArr[$commentType])){
                    $comment[] = $v;
                }
            }
        }

        if(!isset($comment)){
            $comment = [];
        }

        $reply= DB::table('Comment')->where("is_show = 1 and  goods_id = $id and parent_id > 0")->orderBy("add_time desc")->select();
        return view("Home.Goods.ajaxComment", compact("comment", "reply", "sum"));
    }


    /**
     * 评论好评度 百分比
     * @return arr
     */
    public function perComment()
    {
        $arr = $this->comment();
        $n1 = $arr["n1"];//差评个数
        $n2 = $arr["n2"];//中评个数
        $n3 = $arr["n3"];//好评个数
        $n = ($n1 + $n2 + $n3)?($n1 + $n2 + $n3):1;

        $per1 = round($n1/$n, 2)*100;
        $per2 = round($n2/$n, 2)*100;
        $per3 = round($n3/$n, 2)*100;

        $rate = ["per1"=>$per1, "per2"=>$per2, "per3"=>$per3];
        return $rate;
    }



    /**
     * 三级联动  暂时不做了
     * @return string
     */
    public function ajaxAddress()
    {
        $parent_region = DB::table("region")->select("id", "name")->where("level", 1)->get();
        $ip_location = array();
        $city_location = array();
        foreach($parent_region as $k=>$v){
            $c = DB::table("region")->select("id", "name")->where("parent_id",$v->id)->orderBy("id","asc")->get();
            foreach($c as $vo){
                $ip_location[$v->name] = array('id'=>$v->id,'root'=>0,'djd'=>1,'c'=>$vo->id );
            }

        }


        $data = [
                'status' => 1,
                'ip_location'=>$ip_location,
                'city_location'=>$city_location
            ];


        return json_encode($data);


    }










//    public function __construct(\a  $a)
//    {
//        $this->a = $a;
//    }

    /**
     * ajax异步获取规格影响的商品价格
     *
     * @param Request $request
     * @return bool
     */
    public function getPrice(Request $request)
    {
        $input = $request->except('_token');
        $goods_id = $input['goods_id'];
        $key = $input['key'];
        $key = rtrim($key, '_');
        $arr = [
            ['goods_id', '=', $goods_id],
            ['key', '=', $key],
        ];
        $res = DB::table('spec_goods_price')->where($arr)->select('price')->first();
        if($res){
            $data = $res->price;
            return $data;
        }else{
            return false;
        }
    }

}
