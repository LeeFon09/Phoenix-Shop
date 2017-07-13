<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Libs\Pages\Page;

class ArticleController extends Controller
{
    /**
     * 文章列表页面的显示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //查询所有的文章给类型
        $type = DB::table('article_cat')->get();
        //查询所有的文章并做倒序排序
        $count = DB::table('article')->count();
        $pageObj = new Page($count,$request,"admin/Article/articleList");
        $article = DB::table('article')->orderBy('article_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        foreach($article as $k => $v){
            //查询每篇文章的所属类型
            $article[$k]->type = DB::table('article_cat')->where('cat_id', $v->cat_id)->value('cat_name');
        }
        return view('Admin.Article.articleList', compact('article', 'type', 'pageObj'));
    }

    /**
     * 添加文章页面的页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data = DB::table('article_cat')->get()->toArray();
        $arr = [];
        foreach($data as $k=>$v){
            if($v->parent_id == 0){
                $arr[$v->cat_id] = $v->cat_name;
                foreach($data as $m=>$n){
                    if($n->parent_id == $v->cat_id){
                        $arr[$n->cat_id] = '　　'.$n->cat_name;
                    }
                }
            }
        }
        return view('admin.Article.article', compact('arr'));
    }

    /**
     * 处理添加文章的操作
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $arr = [];
        $arr['title'] = $input['title'];
        $arr['cat_id'] = $input['cat_id'];
        $arr['content'] = $input['content'];
        if(!empty($input['thumb'])){
            $arr['thumb'] = $input['thumb'];
        }
        if(!empty($input['keywords'])){
            $arr['keywords'] = $input['keywords'];
        }
        if(!empty($input['link'])){
            $arr['link'] = $input['link'];
        }
        if(!empty($input['publish_time'])){
            $arr['publish_time'] = strtotime($input['publish_time']);;
        }else{
            $arr['publish_time'] = time();
        }
        if(!empty($input['description'])){
            $arr['description'] = $input['description'];
        }
        if ($request->hasFile('thumb')) {
            $file = Input::file('thumb');
            if ($file->isValid()) {
                //检验一下上传的文件是否有效.
                $clientName = $file->getClientOriginalName();        //文件原始名称
                $tmpName = $file->getFileName();                     //文件名称
                $realPath = $file->getRealPath();                    //文件路径
                $extension = $file->getClientOriginalExtension();    //文件扩展名
                $size = $file->getSize();                            //文件大小
                $mime = $file->getMimeType();                        //文件MIME类型
                $year = date('Y', time());
                $date = date('m-d', time());
                $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $extension;
                $fileDir = '/Public/upload/banner/' . $year . '/' . $date . '/';
                $path = $file->move(public_path() . $fileDir, $newName);            //文件保存
                $arr['thumb'] = $fileDir . $newName;
            }
        }
        $res = DB::table('article')->insert($arr);
        if ($res) {
            return redirect('admin/Article/articleList');
        } else {
            return back();
        }
    }

    /**
     * 编辑文章页面的显示
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $res = DB::table('article')->where('article_id', $id)->first();
        $data = DB::table('article_cat')->get()->toArray();
        $arr = [];
        foreach($data as $k=>$v){
            if($v->parent_id == 0){
                $arr[$v->cat_id] = $v->cat_name;
                foreach($data as $m=>$n){
                    if($n->parent_id == $v->cat_id){
                        $arr[$n->cat_id] = '　　'.$n->cat_name;
                    }
                }
            }
        }
        return view('admin.Article.edit', compact('res', 'arr'));
    }

    /**
     * 处理编辑文章的操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->isMethod('put')){
            $input = $request->except('_token', 'put');
            $arr = [];
            $arr['title'] = $input['title'];
            $arr['cat_id'] = $input['cat_id'];
            $arr['content'] = $input['content'];
            if(!empty($input['thumb'])){
                $arr['thumb'] = $input['thumb'];
            }
            if(!empty($input['keywords'])){
                $arr['keywords'] = $input['keywords'];
            }
            if(!empty($input['link'])){
                $arr['link'] = $input['link'];
            }
            if(!empty($input['publish_time'])){
                $arr['publish_time'] = strtotime($input['publish_time']);;
            }else{
                $arr['publish_time'] = time();
            }
            if(!empty($input['description'])){
                $arr['description'] = $input['description'];
            }
            if ($request->hasFile('thumb')) {
                $file = Input::file('thumb');
                if ($file->isValid()) {
                    //检验一下上传的文件是否有效.
                    $clientName = $file->getClientOriginalName();        //文件原始名称
                    $tmpName = $file->getFileName();                     //文件名称
                    $realPath = $file->getRealPath();                    //文件路径
                    $extension = $file->getClientOriginalExtension();    //文件扩展名
                    $size = $file->getSize();                            //文件大小
                    $mime = $file->getMimeType();                        //文件MIME类型
                    $year = date('Y', time());
                    $date = date('m-d', time());
                    $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $extension;
                    $fileDir = '/Public/upload/banner/' . $year . '/' . $date . '/';
                    $path = $file->move(public_path() . $fileDir, $newName);            //文件保存
                    $arr['thumb'] = $fileDir . $newName;
                }
            }
            $res = DB::table('article')->where('article_id', $id)->update($arr);
            if ($res) {
                return redirect('admin/Article/articleList');
            } else {
                return back();
            }
        }
    }

    /**
     * 删除文章的操作
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request,$id)
    {
        $input = $request->except('_token');
        $res = DB::table('article')->where('article_id', $input['article_id'])->delete();
        if($res){
            $data = [
                'status' => 1,
                'msg' => '删除成功'
            ];
            return $data;
        }else{
            $data = [
                'status' => 0,
                'msg' => '删除失败'
            ];
            return $data;
        }
    }

    /**
     * 根据关键字和文章类型查询相应的文章
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        //查询所有的文章类型
        $type = DB::table('article_cat')->get();
        //根据条件查询相应的文章
        $article = DB::table('article')->where(function ($query) use ($request) {
            if(!empty($request->cat_id)){
                $query->where('cat_id', $request->cat_id);
            }
        })->where('title', 'like', '%'.$request->keyword.'%')->orderBy('article_id', 'desc')->get();
        foreach($article as $k => $v){
            //查询文章所属的类型
            $article[$k]->type = DB::table('article_cat')->where('cat_id', $v->cat_id)->value('cat_name');
        }
        return view('Admin.Article.articleList', compact('article', 'type'));
    }

    /**
     * 获取分类
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCategoryList(){
        global $articleCat, $articleCat2, $num, $level;
        $articleCat = DB::table('article_cat')->get()->toArray();
        $num = 1;
        foreach($articleCat as $key => $value){
            if($value->parent_id == 0){
                $level = 1;
                $articleCat[$key]->level = $level;
                $this->getCatTree($value->cat_id, $value);
            }
        }
        // dd($articleCat2);
        return view('Admin.Article.categoryList', compact('articleCat2'));
    }

    /**
     * 查找子孙树
     *
     * @param $id
     * @param $value
     */
    public function getCatTree($id, $value)
    {
        global  $articleCat, $articleCat2, $num, $level;
        //先将该商品类型保存在另一个数组，然后在查询该类型的子类型
         // dd($num);
        $articleCat2[$num] = $value;
        // dump($num);
        $num++;
        //查询子类型
        foreach ($articleCat as $key => $value){
             if($value->parent_id == $id)
             {                  
                //通过回调查询所有的子类型   
                $this->getCatTree($value->cat_id, $value);  
                $value->level = $level + 1;
             }
        }  
    }

    /**
     * 新增分类的显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category()
    {
        $data = DB::table('article_cat')->get()->toArray();
        $arr = [];
        foreach($data as $k=>$v){
            if($v->parent_id == 0){
                $arr[$v->cat_id] = $v->cat_name;
                foreach($data as $m=>$n){
                    if($n->parent_id == $v->cat_id){
                        $arr[$n->cat_id] = '　　'.$n->cat_name;
                    }
                }
            }
        }
        return view('admin.Article.category', compact('arr'));
    }

    /**
     * 处理新增分类的操作
     *
     * @param Request $request
     * @return array
     */
    public function categoryHandle(Request $request)
    {
        //添加分类的操作
        if($request->isMethod('post')){
            $input = $request->except('_token');
            $arr = [];
            if (!empty($input['cat_name'])) {
                $arr['cat_name'] = $input['cat_name'];
            }
            if (is_numeric($input['cat_type'])) {
                $arr['cat_type'] = $input['cat_type'];
            }
            if (is_numeric($input['parent_id'])) {
                $arr['parent_id'] = $input['parent_id'];
            }
            if (is_numeric($input['show_in_nav'])) {
                $arr['show_in_nav'] = $input['show_in_nav'];
            }
            if (!empty($input['sort_order'])) {
                $arr['sort_order'] = $input['sort_order'];
            }
            if (!empty($input['keywords'])) {
                $arr['keywords'] = $input['keywords'];
            }
            if (!empty($input['cat_desc'])) {
                $arr['cat_desc'] = $input['cat_desc'];
            }
            $res = DB::table('article_cat')->insert($arr);
            if ($res) {
                return redirect('admin/Article/categoryList');
            } else {
                return back();
            }
        }

        //删除分类的操作
        if($request->isMethod('delete')){
            $input = $request->except('_token');
            $cat_id = $input['cat_id'];
            $res = DB::table('article_cat')->where('parent_id', '=', $cat_id)->first();
            if($res){
                $data = [
                    'status' => 1,
                    'msg' => '文章分类删除失败，请先删除其子类'
                ];
                return $data;
            }else{
                $re = DB::table('article_cat')->where('cat_id', '=', $cat_id)->delete();
                if ($re) {
                    $data = [
                        'status' => 0,
                        'msg' => '文章分类删除成功'
                    ];
                    return $data;
                } else {
                    $data = [
                        'status' => 2,
                        'msg' => '文章分类删除失败'
                    ];
                    return $data;
                }
            }
        }
    }

    /**
     * 显示编辑文章分类页面及处理编辑文章分类的操作
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function categoryEdit(Request $request,$id)
    {
        //显示编辑文章分类页面
       if($request->isMethod('get')){
           $data = DB::table('article_cat')->get()->toArray();
           $arr = [];
           foreach($data as $k=>$v){
               if($v->parent_id == 0){
                   $arr[$v->cat_id] = $v->cat_name;
                   foreach($data as $m=>$n){
                       if($n->parent_id == $v->cat_id){
                           $arr[$n->cat_id] = '　　'.$n->cat_name;
                       }
                   }
               }
           }
           $res = DB::table('article_cat')->where('cat_id', '=', $id)->first();
           return view('admin.Article.categoryEdit', compact('arr', 'res'));
       }
       //处理编辑文章的操作
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $cat_id = $input['cat_id'];
            $arr = [];
            if (!empty($input['cat_name'])) {
                $arr['cat_name'] = $input['cat_name'];
            }
            if (is_numeric($input['cat_type'])) {
                $arr['cat_type'] = $input['cat_type'];
            }
            if (is_numeric($input['parent_id'])) {
                $arr['parent_id'] = $input['parent_id'];
            }
            if (is_numeric($input['show_in_nav'])) {
                $arr['show_in_nav'] = $input['show_in_nav'];
            }
            if (!empty($input['sort_order'])) {
                $arr['sort_order'] = $input['sort_order'];
            }
            if (!empty($input['keywords'])) {
                $arr['keywords'] = $input['keywords'];
            }
            if (!empty($input['cat_desc'])) {
                $arr['cat_desc'] = $input['cat_desc'];
            }
            $res = DB::table('article_cat')->where('cat_id', '=', $cat_id)->update($arr);
            if ($res) {
                return redirect('admin/Article/categoryList');
            } else {
                return back();
            }
        }
    }

    /**
     * 友情链接列表页面的显示及搜索的操作
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function linkList(Request $request)
    {
        //查询友情链接的总条数
        $totalRows = count(DB::table('friend_link')->get());
        //实例化分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Article/linkList');
        //查询条件查询友情链接
        $list = DB::table('friend_link')->where(function ($query) use ($request, &$totalRows, &$pageObj) {
            //根据友情链接名称查询
            if (!empty($request->keyword)) {
                $query->where('link_name', 'like', '%' . $request->keyword . '%');
                //查询条数
                $totalRows = count(DB::table('friend_link')->where('link_name', 'like', '%' . $request->keyword . '%')->get());
                //实例化分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Article/linkList', 20, 'p', ['keyword' => $request->keyword]);
            }
        })->orderBy('link_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        return view('Admin.Article.linkList', compact('list', 'pageObj'));
    }

    /**
     * 新增友情链接页面的显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function link()
    {
        return view('Admin.Article.link');
    }

    /**
     * 处理友情链接的操作
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function linkHandle(Request $request)
    {
        //添加友情链接的操作
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $arr = [];
            if ($input['link_name']) {
                $arr['link_name'] = $input['link_name'];
            }
            if (is_numeric($input['is_show'])) {
                $arr['is_show'] = $input['is_show'];
            }
            if (is_numeric($input['target'])) {
                $arr['target'] = $input['target'];
            }
            if ($input['link_url']) {
                $arr['link_url'] = $input['link_url'];
            }
            if (is_numeric($input['orderby'])) {
                $arr['orderby'] = $input['orderby'];
            }
            if ($request->hasFile('link_logo')) {
                $file = Input::file('link_logo');
                if ($file->isValid()) {
                    //检验一下上传的文件是否有效.
                    $clientName = $file->getClientOriginalName();        //文件原始名称
                    $tmpName = $file->getFileName();                     //文件名称
                    $realPath = $file->getRealPath();                    //文件路径
                    $extension = $file->getClientOriginalExtension();    //文件扩展名
                    $size = $file->getSize();                            //文件大小
                    $mime = $file->getMimeType();                        //文件MIME类型
                    $year = date('Y', time());
                    $date = date('m-d', time());
                    $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $extension;
                    $fileDir = '/Public/upload/article/' . $year . '/' . $date . '/';
                    $path = $file->move(public_path() . $fileDir, $newName);            //文件保存
                    $arr['link_logo'] = $fileDir . $newName;
                }
            }
            $res = DB::table('friend_link')->insert($arr);
            if ($res) {
                return redirect('admin/Article/linkList');
            } else {
                return back();
            }
        }

        //删除友情链接的操作
        if ($request->isMethod('delete')) {
            $input = $request->except('_token');
            $res = DB::table('friend_link')->where('link_id', $input['link_id'])->delete();
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

        //编辑友情链接的操作
        if($request->isMethod('put')){
            $input = $request->except('_token', '_method');
            $arr = [];
            if (!empty($input['link_name'])) {
                $arr['link_name'] = $input['link_name'];
            }
            if (is_numeric($input['is_show'])) {
                $arr['is_show'] = $input['is_show'];
            }
            if (is_numeric($input['target'])) {
                $arr['target'] = $input['target'];
            }
            if (!empty($input['link_url'])) {
                $arr['link_url'] = $input['link_url'];
            }
            if (is_numeric($input['orderby'])) {
                $arr['orderby'] = $input['orderby'];
            }
            if ($request->hasFile('link_logo')) {
                $file = Input::file('link_logo');
                if ($file->isValid()) {
                    //检验一下上传的文件是否有效.
                    $clientName = $file->getClientOriginalName();        //文件原始名称
                    $tmpName = $file->getFileName();                     //文件名称
                    $realPath = $file->getRealPath();                    //文件路径
                    $extension = $file->getClientOriginalExtension();    //文件扩展名
                    $size = $file->getSize();                            //文件大小
                    $mime = $file->getMimeType();                        //文件MIME类型
                    $year = date('Y', time());
                    $date = date('m-d', time());
                    $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $extension;
                    $fileDir = '/Public/upload/article/' . $year . '/' . $date . '/';
                    $path = $file->move(public_path() . $fileDir, $newName);            //文件保存
                    $arr['link_logo'] = $fileDir . $newName;
                }
            }
            $res = DB::table('friend_link')->where('link_id', $input['link_id'])->update($arr);
            if ($res) {
                return redirect('admin/Article/linkList');
            } else {
                return back();
            }
        }
    }

    /**
     * 编辑友情链接页面的显示
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function linkEdit(Request $request, $id)
    {
        $data = DB::table('friend_link')->where('link_id', '=', $id)->first();
        return view('Admin.Article.linkEdit', compact('data'));
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
        //修改友情链接是否新窗口打开的状态
        if(isset($input['link_id']) && $input['target']==1){
            $data = ['target' => 0];
            $res = DB::table('friend_link')->where('link_id', $input['link_id'])->update($data);
        }elseif(isset($input['link_id']) && $input['target']==0){
            $data = ['target' => 1];
            $res = DB::table('friend_link')->where('link_id', $input['link_id'])->update($data);
        }
        //修改文章列表是否显示的状态
        if(isset($input['article_id']) && $input['is_open']==1){
            $data = ['is_open' => 0];
            $res = DB::table('article')->where('article_id', $input['article_id'])->update($data);
        }elseif(isset($input['article_id']) && $input['is_open']==0){
            $data = ['is_open' => 1];
            $res = DB::table('article')->where('article_id', $input['article_id'])->update($data);
        }
        //修改文章分类是否显示的状态
        if(isset($input['cat_id']) && $input['show_in_nav']==1){
            $data = ['show_in_nav' => 0];
            $res = DB::table('article_cat')->where('cat_id', $input['cat_id'])->update($data);
        }elseif(isset($input['cat_id']) && $input['show_in_nav']==0){
            $data = ['show_in_nav' => 1];
            $res = DB::table('article_cat')->where('cat_id', $input['cat_id'])->update($data);
        }
        //修改广告位的状态
        if(isset($input['position_id']) && $input['is_open']==1){
            $data = ['is_open' => 0];
            $res = DB::table('ad_position')->where('position_id', $input['position_id'])->update($data);
        }elseif(isset($input['position_id']) && $input['is_open']==0){
            $data = ['is_open' => 1];
            $res = DB::table('ad_position')->where('position_id', $input['position_id'])->update($data);
        }
        //修改广告是否显示的状态
        if(isset($input['ad_id']) && $input['enabled']=="1"){
            $data = ['enabled' => 0];
            $res = DB::table('ad')->where('ad_id', $input['ad_id'])->update($data);
        }elseif(isset($input['ad_id']) && $input['enabled']=="0"){
            $data = ['enabled' => 1];
            $res = DB::table('ad')->where('ad_id', $input['ad_id'])->update($data);
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
