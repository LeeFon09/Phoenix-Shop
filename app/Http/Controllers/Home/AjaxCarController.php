<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;

class ajaxCarController extends Controller
{

    /**
     * 点击加入购物车的ajax
     * @param Request $request
     * @return array
     */
    public function goodsBuy(Request $request)
    {

        $ajax = Input::get("aa");
        $str=urldecode($ajax);
        parse_str($str);
        //ajax接受的值为空
        if(!$str){
            $data = [
                'status' => -1,
                'msg'    => '购买失败，请稍后重试！-1',
            ];
            return $data;
        }

        //将商品类型key值，购买数量，商品id存入session中
        if(empty($goods_spec)){
            $spec = "";
        }else{
            sort($goods_spec);
            $spec = implode("_",$goods_spec);

        }
        session(["test" => $spec]);
        $num = $goods_num;
        $id = $goods_id;

        //判断库存
        $store_count = DB::table("spec_goods_price")->where(["goods_id"=>$id, "key"=>$spec])->value("store_count");
        if($num>$store_count){
            $data = [
                "status" => -2,
                "msg"    => "库存不足,请联系卖家！",
            ];
            return $data;
        }


        //判断是否存到session中,存在就把下一个商品压入数组末尾
        if($request->session()->exists("cart")){
            $shops = $request->session()->get("cart");
            foreach($shops as $k => $v){
                //如果有重复商品，就将数量相加
                if($v["id"] == $id && $v["spec"] == $spec){
                    $shops[$k]["num"] = $v["num"] +$num;
                    session(["cart" => $shops]);
//                    return session("cart");
                    $data = [
                        'status' => 1,
                        'msg'    => '购买成功！',
                    ];
                    return $data;

                }else{
                    $next = [
                        "spec"     => $spec,
                        "num"      => $num,
                        "id"       => $id,
                        "selected" => 1,
                        "sel_all"  => true
                    ];
                }
            }
            $arr = session("cart");
            array_push($arr, $next);
        }else{
            $arr = [[
                "spec"     => $spec,
                "num"      => $num,
                "id"       => $id,
                "selected" => 1,
                "sel_all"  => true
            ]];

        }

        session(["cart" => $arr]);
        $data = [
            'status' => 1,
            'msg'    => '购买成功！',
        ];
        return $data;

    }

    /**
     * 点击加入购物车后出现的弹窗
     * @return array
     */
    public function shopcardLayer()
    {
        //select * from `__PREFIX__goods` where  is_recommend = 1 limit 4
        $hot = DB::table("goods")->select("goods_name", "goods_id", "shop_price")
                ->where("is_recommend", 1)->limit(4)->get();
        return view("Home.Goods.openAddCart",compact("hot"));
    }


    /**
     * 改变session对应key和spec的num值(商品数量)
     * @return mixed
     */
    public function getSession(Request $request)
    {
        $shops = $request->session()->get("cart");
        $all = Input::all();
        $keys = $all["keys"];
        $num = $all["num"];
        foreach($shops as $k=>$v){
            if($keys == $k){
                
                $shops[$k]["num"] = $num;
                session(["cart" => $shops]);
                $data = [
                    'status' => 1,
                    'msg'    => '数量改变成功！',
                ];
                return $data;
            }
        }

    }

    /**
     * 删除购物车单商品
     * @param Request $request
     * @return array
     */
    public function delSession(Request $request){
        $shops = $request->session()->get("cart");
        $id = Input::except("_token")["ids"];
        $del = Input::get("del");

        foreach($shops as $k=>$v){
//            if($v["id"] == $id){
//                unset($shops[$k]);
//            }
            if($k == $id){
                unset($shops[$k]);
            }
        }

        $shops= array_values($shops);
        if(!empty($shops)){

            session(["cart" => $shops]);

        }else{

            $request->session()->forget('cart');
        }

        $data = [
            "status" => 1,
            "msg"    => "删除成功"
        ];
        return $data;

    }

    /**
     * 全选
     * @param Request $request
     * @return int
     */
    public function selAll(Request $request)
    {
        $shops = $request->session()->get("cart");
        $sel = Input::get("sel");
        $shops[0]["sel_all"] = $sel;
        foreach($shops as $k=>$v){
            $shops[$k]["selected"] = $sel;
        }
        session(["cart" => $shops]);
        return 1;

    }


    /**
     * 删除所选中的商品
     * @param Request $request
     * @return array
     */
    public function delMore(Request $request)
    {
        $shops = $request->session()->get("cart");
        $chk = Input::get("chk");
        foreach($chk as $v){
            unset($shops[$v]);
        }

        $shops= array_values($shops);
        if(!empty($shops)){

            session(["cart" => $shops]);

        }else{

            $request->session()->forget('cart');
        }

        $data = [
            "status" => 1,
            "msg"    => "删除成功"
        ];
        return $data;
    }

    /**
     * 确认订单页收货地址
     */
    public function cneeInformation()
    {
        $lists = DB::table('user_address')->where('user_id',session('user')->user_id)
            ->orderBy('is_default','desc')->get();
        return view("Home.cart.ajaxAddress",compact('lists'));
    }


    /**
     * 显示头部购物车列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajaxHeaderCart()
    {
        //购物车
        $total = 0;
        $cart = session("cart")? session("cart"): [];
        foreach($cart as $k => $v){
            $goods_id = $v["id"];
            $spec = $v["spec"];
            if(empty($spec)){
                //如果商品规格类型没有有设置
                $cateList[] = DB::table("goods")->where(["goods.goods_id"=>$goods_id])
                    ->select("original_img", "goods_name","shop_price")->get();
            }else{
                $cateList[] = DB::table("goods")->join("spec_goods_price", "goods.goods_id", "=", "spec_goods_price.goods_id")
                    ->where(["goods.goods_id"=>$goods_id, "spec_goods_price.key"=>$spec])
                    ->select("spec_goods_price.price","goods.goods_name",
                        "goods.original_img")->get();
            }
            $cateList[$k][0]->num = $v["num"];

            if(isset($cateList[$k][0]->price)){
                $total += $cateList[$k][0]->price * $v["num"];
            }else{
                $total += $cateList[$k][0]->shop_price * $v["num"];
            }

            $number = $k+1;
        }

        if(!isset($cateList)){
            $cateList = [];
            $number = 0;
        }
        return view('Home.Public.ajaxHeaderCart',["cateList" => $cateList,"total" => $total, "number" => $number]);
    }


    /**
     * 删除头部购物车商品
     * @return int
     */
    public function ajaxDelCart()
    {
        $session_id = Input::get("session_id");
        $cart = session("cart");
        array_splice($cart, $session_id, 1);
//        return count($cart);
        if(count($cart)){
            session(["cart" => $cart]);
            return 1;
        }
    //home/goods/goodslist/2
        session()->forget("cart");
        return 1;

    }


    /**
     * 处理规格后退默认选中情况
     * @return array
     */
    public function back()
    {
        $test = session("test");
        $arr = explode("_", $test);
        return $arr;
    }


}
