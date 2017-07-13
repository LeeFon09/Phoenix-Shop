<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


class CartController extends Controller
{
    /**
     * 我的购物车界面
     * @return view
     */
    public function showCart(Request $request)
    {

        return view('Home.Cart.cart');
    }

    /**
     * 将数据遍历到购物车界面
     * @param Request $request
     * @return mix
     */
    public function ajaxList(Request $request)
    {

        $shops = $request->session()->get("cart");

//        $request->session()->forget("cart");
        if(empty($shops)){
            $shops = [];
            $sel_all = "";
        }else{
            $sel_all = $shops[0]["sel_all"];
        }



        //选择框  单选框
        $keys = Input::get("selected");

        foreach ($shops as $k => $v){
            $spec = $v["spec"];         //规格key
            $num = $v["num"];           //购买数量
            $id = $v["id"];             //商品id
            $sel = $v["selected"];      //单选框
//            $sel_all = $v["sel_all"]; //多选框

            $nums[] = $num;
            $selected[] = $sel;
            if(empty($spec)){
                //如果商品规格类型没有有设置
                $all[] = DB::table("goods")->where(["goods.goods_id"=>$id])
                    ->select("goods_id", "original_img", "goods_name",
                        "market_price" ,"shop_price","store_count")
                    ->get();
            }else{
                $all[] = DB::table("goods")->join("spec_goods_price", "goods.goods_id", "=", "spec_goods_price.goods_id")
                    ->where(["goods.goods_id"=>$id, "spec_goods_price.key"=>$spec])
                    ->select("goods.goods_id", "spec_goods_price.price", "spec_goods_price.store_count", "spec_goods_price.key_name",
                        "spec_goods_price.store_count", "goods.original_img", "goods.goods_name", "goods.market_price")->get();
            }

        }

        $total = 0;
        $marketPrice = 0;
        if(empty($all)){
            $all = [];
        }
        foreach($all as $k => $v){

            if(isset($v[0]->price)){
                $v[0]->subtotal = $v[0]->price * $nums[$k]; //小计
            }else{
                $v[0]->subtotal = $v[0]->shop_price * $nums[$k]; //小计
            }
            $v[0]->goods_num = $nums[$k];

            //当第一次进入本页面时候
            if($shops[$k]["selected"] == 1 && $keys == -1){
//                $shops[$k]["selected"] = 1;
                $total += $v[0]->subtotal; //总计
                $marketPrice += $v[0]->market_price * $nums[$k];  //市场总价
            }

            //单选框
            if($keys == $k){
                $shops[$keys]["selected"] = $selected[$keys]==1 ? 0:1;
                $selected[$keys] = $selected[$keys]==1 ? 0:1;
            }
            if($selected[$k] == 1 && $keys>-1){
                $total += $v[0]->subtotal; //总计
                $marketPrice += $v[0]->market_price * $nums[$k];  //市场总价
            }
            session(["cart" => $shops]);
        }

        $priceSpread = $marketPrice - $total; //差价----市场-总计
        return view("Home.Cart.ajaxCartList",compact("all", "total", "priceSpread", "selected", "sel_all"));
    }


    /**
     * 购物车第二步--填写核对单
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkOrder(Request $request)
    {
//        parent::isLoginTimeout();//判断是否登陆或超时
//        dump($request->all());
//        parent::isValidAccess(md5("checkorder"),$request->input('token'));
//        $user = $request->session()->get("user");
//        $user_id = $user->user_id;
        $cart = $request->session()->get("cart");
        $total = 0;

        foreach($cart as $v){
            $goods_id = $v["id"];
            $spec = $v["spec"];
            if($v["selected"] == 0){
                continue;
            }
            if(empty($spec)){
                //如果商品规格类型没有有设置
                $goods = DB::table("goods")->where(["goods.goods_id"=>$goods_id])
                    ->select( "goods_name", "market_price", "shop_price", "goods_sn")
                    ->get();
            }else{
                $goods = DB::table("goods")->join("spec_goods_price", "goods.goods_id", "=" ,"spec_goods_price.goods_id")
                    ->select("goods.goods_sn", "goods.goods_name", "goods.shop_price", "spec_goods_price.price","spec_goods_price.key",
                        "spec_goods_price.key_name", "goods.original_img")
                    ->where(["goods.goods_id" => $goods_id, "spec_goods_price.key" => $spec])->first();
            }
            $name = $goods->goods_name;
            $price = empty($spec) ? $goods->shop_price:$goods->price;//商品属性是否为空? 商品价格：规格价格
            $num = $v["num"];
            $subtotal = $num*$price;
            $cartItem = ["goods_id" =>$goods_id,"goods_sn"=>$goods->goods_sn,"goods_name" => $name,"goods_price" => $price,
                "goods_num" => $num,"total_price" => $subtotal,"benefit_price"=>$subtotal,"original_img"=>$goods->original_img];
            if(!empty($spec)){
                $cartItem = array_merge($cartItem,["spec_key"=>$goods->key,"spec_key_name"=>$goods->key_name]);
            }
            $cartList[]=$cartItem;
            $total += $subtotal;
        }
//        dump($cart);
        session(['order'=>compact("cartList", "total")]);
//        dd(compact("cartList", "total"));
        return view('Home.Cart.checkOrder', compact("cartList", "total"));
    }
    /*
    0 => {#466 ▼
      +"goods_sn": "TP000000"
      +"goods_name": "Apple iPhone 6s Plus 16G 玫瑰金 移动联通电信4G手机"
      +"market_price": "6107.00"
      +"price": "0.08"
      +"key_name": "网络:4G 颜色:玫瑰金 内存:128G"
      +"bar_code": ""
    }
    */
    public function doCommitOrder(){
//        parent::isLoginTimeout();//判断是否登陆或超时

        $jsonArr = json_decode(Input::get('data'));
        $order_sn = date('YmdHis',time()).mt_rand(1000,9999);
        $user_id = session('user')->user_id;
        $address_id = $jsonArr->address_id;
        $total_amount = $goods_price = session('order')['total'];
        $commit_time = date("Y-m-d H:i:s",time());
        $valid_time = date("Y-m-d",strtotime("+3 day"));
        $order = compact('order_sn','user_id','address_id','goods_price','total_amount','commit_time','valid_time');
        $oid = null;
        $orderDetail = session('order')['cartList'];

        /*
        DB::transaction(function() use ($order,$orderDetail,&$oid,$user_id){
            $oid = DB::table('order')->insertGetId($order);
            $arr = ["order_id"=>$oid,"user_id"=>$user_id,"add_time"=>date("Y-m-d H:i:s",time())];
            $detail=array_map(function($item)use ($arr){
                return array_merge($arr,$item);
            },$orderDetail);
            DB::table('order_detail')->insert($detail);
        });
        */
    //此处应从redis缓存中删除购物车数据
        $cart = session('cart');
//        dump($cart);
//        dump($orderDetail);
        foreach( $cart as $k=>$arr){
            if($arr['selected'] == 1){ //unset($cart[$k]);
                unset($cart[$k]);
            }
        }
        $newcart = array_values($cart);
//        dump($newcart);
        /*
        session(['cart'=>$newcart]);
        dump(session('cart'));*/
//        return response()->json(["status"=>0,"info"=>"订单提交成功!","oid"=>7]);//$oid
        $arr = ["status"=>0,"info"=>"订单提交成功!","oid"=>7];
        return $arr;
    }
    public function checkPayment($orderId)
    {
        $order = DB::table('order')->where('order_id',$orderId)->first();
//        dd($order);

        return view('Home.Cart.checkPayment',compact('order'));
    }

}
