<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\Libs\Pages\Page;

class CommentController extends Controller
{
    public function index (Request $request)
    {
        //查询商品评价的总条数
        $totalRows = count(DB::table('comment')->get());
        //创建一个分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Comment/index');
    	//查询所有的商品评价内容
    	$comment = DB::table('comment')->where(function($query) use($request, &$totalRows, &$pageObj) {
            if(!empty($request->content) && !empty($request->nickname)){
                $query->where('content', 'like', '%'.$request->content.'%')->where('username', 'like', '%'.$request->nickname.'%');
                //查询商品品牌的总条数
                $totalRows = count(DB::table('comment')->where('content', 'like', '%'.$request->content.'%')->where('username', 'like', '%'.$request->nickname.'%')->get());
                //创建一个分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Comment/index',10,'p',['content'=>$request->content, 'nickname'=>$request->nickname]);
            }
            if(!empty($request->content) && empty($request->nickname)){
                 $query->where('content', 'like', '%'.$request->content.'%');
                //查询商品品牌的总条数
                $totalRows = count(DB::table('comment')->where('content', 'like', '%'.$request->content.'%')->get());
                //创建一个分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Comment/index',10,'p',['content'=>$request->content]);
            }
            if(empty($request->content) && !empty($request->nickname)){
                 $query->where('username', 'like', '%'.$request->nickname.'%');
                //查询商品品牌的总条数
                $totalRows = count(DB::table('comment')->where('username', 'like', '%'.$request->nickname.'%')->get());
                //创建一个分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Comment/index',10,'p',['nickname'=>$request->nickname]);
            }
        })->where('parent_id', 0)->orderBy('comment_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
    	foreach($comment as $k => $v){
    		//查询每条评价对应的商品
    		$comment[$k]->goodsName = DB::table('goods')->where('goods_id', $v->goods_id)->value('goods_name');
    	}
    	return view('Admin.Comment.index', compact('comment', 'pageObj'));
    }

    public function del($id)
    {
        //根据评论ID删除该评论和该评论下的回复
        $bool = DB::table('comment')->where('comment_id', $id)->orwhere('parent_id', $id)->delete();
        if($bool){
            return redirect(url('admin/Comment/index'))->with('info', '删除成功');
        }else{
            return back()->with('info', '删除失败');
        }
    }

    public function detail($id)
    {
        //根据ID查询商品的评论
        $goods_id = DB::table('comment')->where('comment_id', $id)->value('goods_id');
        $order_id = DB::table('comment')->where('comment_id', $id)->value('order_id');
        $comment = DB::table('comment')->where('goods_id', $goods_id)->where('order_id', $order_id)->orderBy('comment_id')->get();
        return view('Admin.Comment.detail', compact('comment'));
    }

    public function reply($id, Request $request)
    {
        //获取回复信息
        $content = $request->content;
        //获取该商品评论下的商品信息和用户信息等
        $tmp = DB::table('comment')->where('comment_id', $id)->get();
        //将回复信息插到数据库中
        $bool = DB::table('comment')->insert([
                'goods_id' => $tmp[0]->goods_id,
                'username' => session('admin')->user_name,
                'content' => $content,
                'order_id' => $tmp[0]->order_id,
                'add_time' => time(),
                'parent_id' => $id
            ]);
        if($bool){
            return redirect(url('admin/comment/detail/'.$id))->with('info', '回复成功');
        }else{
            return back()->with('info', '回复失败');
        }
    }

    /**
     * ajax异步更新状态
     *
     * @param Request $request
     * @return array
     */
    public function changeStatus(Request $request)
    {
        $input = $request->except('_token');
        //修改商品评论是否显示的状态
        if(isset($input['comment_id']) && $input['is_show']==1){
            $data = ['is_show' => 0];
            $res = DB::table('comment')->where('comment_id', $input['comment_id'])->update($data);
        }elseif(isset($input['comment_id']) && $input['is_show']==0){
            $data = ['is_show' => 1];
            $res = DB::table('comment')->where('comment_id', $input['comment_id'])->update($data);
        }
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
