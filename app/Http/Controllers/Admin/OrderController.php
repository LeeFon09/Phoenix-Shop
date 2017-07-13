<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Libs\Pages\Page;

class OrderController extends Controller
{
    /**
     * 订单列表的显示及其条件筛选的操作
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $input = $request->except('_token');
        //查询订单的总条数
        $totalRows = count(DB::table('order')->get());
        //实例化分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Order/index');
        //根据条件筛选订单
        $orders = DB::table('order')->where(function ($query) use ($request, &$totalRows, &$pageObj) {
            //根据处理状态筛选
            if ($request->handle_status > -1) {
                $query->where('handle_status', '=', $request->handle_status);
                //查询条数
                $totalRows = count(DB::table('order')->where('handle_status', '=', $request->handle_status)->get());
                //实例化分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Order/index', 20, 'p', ['handle_status' => $request->handle_status]);
            }
            //根据是否有效筛选
            if ($request->is_valid > -1) {
                $query->where('is_valid', '=', $request->is_valid);
                //查询条数
                $totalRows = count(DB::table('order')->where('is_valid', '=', $request->is_valid)->get());
                //实例化分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Order/index', 20, 'p', ['is_valid' => $request->is_valid]);
            }
            //根据下单时间筛选
            if (!empty($request->commit_time)) {
                $time = explode(' ', $request->commit_time);
                $start_time = $time[0];
                $start_time .= ' 00:00:00';
                $end_time = $time[2];
                $end_time .= ' 00:00:00';
                $query->whereDate('commit_time', '>=', $start_time)->whereDate('commit_time', '<=', $end_time);
                //查询条数
                $totalRows = count(DB::table('order')->whereDate('commit_time', '>=', $start_time)->whereDate('commit_time', '<=', $end_time)->get());
                //实例化分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Order/index', 20, 'p', ['commit_time' => $request->commit_time]);
            }
        })->orderBy('order_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        foreach ($orders as $k => $order) {
            $userAddress = DB::table('user_address')->where('user_id', '=', $order->user_id)->select('consignee')->first();
            $orders[$k]->consignee = $userAddress->consignee;
        }
        return view('Admin.Order.index', compact('orders', 'input', 'pageObj'));
    }

    /**
     * 订单列表处理状态的操作
     *
     * @param Request $request
     * @return array
     */
    public function orderHandle(Request $request)
    {
        $input = $request->except('_token');
        $order_ids = $input['order_ids'];
        $time = time();
        $arr = [
            'handle_status' => 4,
            'order_status' => 5,
            'shipping_time' => date('Y-m-d H:i:s', $time),
        ];
        foreach($order_ids as $order_id){
            $res = DB::table('order')->where('order_id', $order_id)->update($arr);
            if(!$res){
                continue;
            }
        }
        $data = [
            'status' => 1,
            'msg' => '操作成功！'
        ];
        return $data;
    }

    /**
     * 退货单列表页显示及其筛选操作
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function returnList(Request $request)
    {
        //退货单列表页显示
        if ($request->isMethod('get')) {
            $return_goods = DB::table('return_goods')->where('status', '=', 0)->orderBy('id', 'desc')->select('id', 'order_sn', 'goods_id', 'type', 'addtime', 'status')->get();
            foreach ($return_goods as $k => $return_good) {
                $goods = DB::table('return_goods')->join('goods', 'return_goods.goods_id', '=', 'goods.goods_id')->where('return_goods.goods_id', '=', $return_good->goods_id)->select('goods.goods_name')->first();
                $return_goods[$k]->goods_name = $goods->goods_name;
            }
            return view('Admin.Order.returnList', compact('return_goods', 'goods'));
        }
        //退货单列表页的筛选操作
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $arr = [];
            $arr['status'] = $input['status'];
            if (!empty($input['order_sn'])) {
                $arr['order_sn'] = $input['order_sn'];
            }
            $return_goods = DB::table('return_goods')->where($arr)->orderBy('id', 'desc')->select('id', 'order_sn', 'goods_id', 'type', 'addtime', 'status')->get();
            foreach ($return_goods as $k => $return_good) {
                $goods = DB::table('return_goods')->join('goods', 'return_goods.goods_id', '=', 'goods.goods_id')->where('return_goods.goods_id', '=', $return_good->goods_id)->select('goods.goods_name')->first();
                $return_goods[$k]->goods_name = $goods->goods_name;
            }
            return view('Admin.Order.returnList', compact('return_goods'));
        }
    }

    /**
     * 删除退货单信息
     *
     * @param Request $request
     * @return array
     */
    public function returnDel(Request $request)
    {
        if($request->isMethod('post')){
            $input = $request->except('_token');
            $id = $input['id'];
            $res = DB::table('return_goods')->where('id', '=', $id)->delete();
            if ($res) {
                $data = [
                    'status' => 1,
                    'msg' => '删除成功'
                ];
                return $data;
            } else {
                $data = [
                    'status' => 0,
                    'msg' => '删除失败'
                ];
                return $data;
            }
        }
    }

    /**
     * 查看退货单的详情
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function returnInfo(Request $request, $id)
    {
        $return_goods = DB::table('return_goods')->where('id', '=', $id)->select('id', 'order_sn', 'goods_id', 'type', 'addtime', 'status', 'reason', 'imgs', 'remark', 'user_id')->first();
        $goods = DB::table('goods')->where('goods_id', '=', $return_goods->goods_id)->select('goods_name')->first();
        $users = DB::table('users')->where('user_id', '=', $return_goods->user_id)->select('nickname')->first();
        $return_goods->goods_name = $goods->goods_name;
        $return_goods->nickname = $users->nickname;
        $arr = explode(',', $return_goods->imgs);
        $return_goods->imgs = $arr;
        return view('Admin.Order.returnInfo', compact('return_goods'));
    }

    /**
     * 处理编辑退货单信息的操作
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function returnEdit(Request $request)
    {
        $input = $request->except('_token', 'reason');
        $arr = [];
        $arr['type'] = $input['type'];
        $arr['status'] = $input['status'];
        if (!empty($input['remark'])) {
            $arr['remark'] = $input['remark'];
        }
        $res = DB::table('return_goods')->where('id', '=', $input['id'])->update($arr);
        if ($res) {
            return redirect('admin/Order/return_list');
        } else {
            return back();
        }
    }

    /**
     * ajax异步改变状态
     *
     * @param Request $request
     * @return array
     */
    public function changeStatus(Request $request)
    {
        $input = $request->except('_token');
        if($input['is_valid']==1){
            $data = ['is_valid' => 0];
        }elseif($input['is_valid']==0){
            $data = ['is_valid' => 1];
        }
        $res = DB::table('order')->where('order_id', $input['order_id'])->update($data);
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
