<?php

namespace App\Http\Controllers\Home;

use App\Libs\Pages\Page;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    const ORDER_STATUS = ["未付款","待付款","已付款","待发货","已发货","待收货","已收货","待评价","已评价","交易成功","交易完结","删除订单"];
    //处理状态(0.未付款 2.已付款 4.已发货 6.已收货 8.已评价 10.交易完结)
    //订单状态(1.待付款 3.待发货 5.待收货 7.待评价 9.交易成功 11.删除订单)

    /**
     * 订单列表页面的显示
     *
     * @param Request $request
     * @param int $status
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderList(Request $request, $status=0)
    {
        $userId = session('user')->user_id;
        if($status == 0){
            $where = ['user_id'=>$userId,'is_valid'=>1];
        }else{
            $where = ['user_id'=>$userId,'is_valid'=>1,'order_status'=>$status];
        }
        $count = DB::table('order')->where($where)->count();
        $pageObj = new Page($count,$request,"home/order/orderList",5);//分页类实例化
        $orderList = DB::table('order')
            ->where($where)->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        foreach ($orderList as $obj){
            $obj->details = DB::table('order_detail')->where('order_id',$obj->order_id)->get();
        }
        return view('Home.User.orderList',['orderList'=>$orderList,'pageObj'=>$pageObj,'status'=>self::ORDER_STATUS]);
    }

    /**
     * 订单明细页面跳转
     * @param $orderId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderDetail($orderId)
    {
        $orderInfo = DB::table('order')->where('order_id',$orderId)->first();
        $address = DB::table('user_address')->where('address_id',$orderInfo->address_id)->first();
        $details = DB::table('order_detail')->where('order_id',$orderId)->get();

        return view('Home.User.orderDetail',compact('orderInfo','details','address'));
    }

    /**
     * 取消订单(Ajax)
     * @param Request $request
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function concelOrder(Request $request,$orderId)
    {
        $status = $request->input('status');
        DB::transaction(function() use ($orderId) {
            DB::table('order')->where('order_id',$orderId)->update(["is_valid" => 0]);
            DB::table('order_detail')->where('order_id',$orderId)->update(["is_valid" => 0]);
        });

        return response()->json(['status'=>0,'msg'=>"取消成功!"]);;
    }

    /**
     * 订单支付Ajax要加载的页面
     * @param $orderId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toPay($orderId)
    {
        $orderInfo = DB::table('order')->where('order_id',$orderId)->first();
        return view('Home.Payment.payment',compact('orderInfo'));
    }

    /**
     * 订单支付(Ajax)
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function doPay($orderId)
    {
//        dd($orderId);
        DB::table('order')->where(['order_id'=>$orderId])->update(["order_status"=>3,"handle_status"=>2,"pay_time"=>date('Y-m-d H:i:s',time())]);
        return response()->json(['status'=>0,'msg'=>"支付成功!"]);
    }

    /**
     * 订单确认(Ajax)
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmOrder($orderId)
    {
        DB::table('order')->where(['order_id'=>$orderId])->update(["order_status"=>7,"handle_status"=>6,"confirm_time"=>date('Y-m-d H:i:s',time())]);
        return response()->json(['status'=>0,'msg'=>"收货成功!"]);
    }

}
