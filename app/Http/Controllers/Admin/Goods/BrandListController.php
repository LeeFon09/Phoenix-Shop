<?php

namespace App\Http\Controllers\Admin\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\Libs\Pages\Page;

class BrandListController extends Controller
{
    /**
     * 显示所有的商品品牌
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //查询商品品牌的总条数
        $totalRows = count(DB::table('brand')->get());
        //创建一个分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Goods/brandList');
        //查询所有的商品品牌
        $brand = DB::table('brand')->where(function($query) use($request, &$totalRows, &$pageObj) {
            if(!empty($request->keyword)){
                $query->where('name', 'like', '%'.$request->keyword.'%');
                //查询商品品牌的总条数
                $totalRows = count(DB::table('brand')->where('name', 'like', '%'.$request->keyword.'%')->get());
                //创建一个分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Goods/brandList',10,'p',['keyword'=>$request->keyword]);
            }
        })->orderBy('id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        return view('Admin.Goods.brandList', compact('brand', 'pageObj'));
    }

    /**
     * 显示创建一个新品牌的表格
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	//查询所有等级为1的商品类型，供新添加的品牌选择
    	$type = DB::table('goods_category')->where('level',1)->get();
        return view('Admin.Goods.addBrand',compact('type'));
    }

    /**
     * 保存新的商品品牌信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	//验证添加的表单信息
        $this->validate($request, [
		    'name' => 'required|unique:brand',
		    'cat_id' => 'required',
		    'logo' => 'required',
		],
		[
			'name.required' => '品牌名称不能为空',
			'name.unique' => '已经存在该品牌',
			'cat_id.required' => '请选择所属分类',
			'logo.required' => '请上传品牌logo',	
		]);
        //将表单信息存在数组中，以便添加到数据表中
		$arr['name'] = $request->name;
		$arr['parent_cat_id'] = $request->parent_cat_id;
		$arr['cat_id'] = $request->cat_id;
		if(!empty($request->url)){
			$arr['url'] = $request->url;
		}else{
			$arr['url'] = '';
		}
		$arr['desc'] = $request->desc;
		$arr['sort'] = $request->sort?$request->sort:50;
		$arr['cat_name'] = DB::table('goods_category')->where('id', $request->cat_id)->value('name');

		//检测是否有文件上传
        if($request->hasFile('logo')) {
            //拼接文件夹路径
            $destinationPath = './Public/upload/brand/'.date('Y').'/'.date('m-d').'/';
            //拼接文件路径
            $fileName = time().rand(100000, 999999);
            //获取上传文件的后缀
            $suffix = $request->file('logo')->getClientOriginalExtension();
            //文件的完整的名称
            $fullName = $fileName.'.'.$suffix;
            //保存文件
            $request->file('logo')->move($destinationPath, $fullName);
            //拼接文件上传之后的路径  
            $arr['logo'] = $destinationPath.$fullName;   
        }
        //往数据表中添加品牌信息
        $bool = DB::table('brand')->insert([
        		'name' => $arr['name'],
        		'parent_cat_id' => $arr['parent_cat_id'],
        		'cat_id' => $arr['cat_id'],
        		'url' => $arr['url'],
        		'desc' => $arr['desc'],
        		'logo' => $arr['logo'],
        		'sort' => $arr['sort'],
        		'cat_name' => $arr['cat_name']
        	]);
        //判断是否添加成功
        if($bool){
        	return redirect(url('admin/Goods/brandList'))->with('info','添加成功');
        }else{
        	return back()->with('info', '添加失败');
        }
    }

    /**
     * 显示修改商品品牌的表格
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	//查询所有等级为1的商品类型，供新添加的品牌选择
    	$type = DB::table('goods_category')->where('level',1)->get();
    	//根据商品品牌ID查询要修改的品牌
        $brand = DB::table('brand')->where('id',$id)->get();
        return view('Admin.Goods.editBrand', compact('brand', 'type'));
    }

    /**
     * 更新商品品牌信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //验证添加的表单信息
        $this->validate($request, [
		    'name' => 'required',
		    'parent_cat_id' => 'required',
		    'cat_id' => 'required',
		    'desc' => 'required',
		    'sort' => 'required',
		],
		[
			'name.required' => '品牌名称不能为空',
			'parent_cat_id.required' => '请选择分类',
			'cat_id.required' => '请选择分类',	
			'sort.required' => '排序不能为空',	
		]);
        //将表单信息存在数组中，以便添加到数据表中
		$arr['name'] = $request->name;
		$arr['parent_cat_id'] = $request->parent_cat_id;
		$arr['cat_id'] = $request->cat_id;
		if(!empty($request->url)){
			$arr['url'] = $request->url;
		}else{
			$arr['url'] = '';
		}
		$arr['desc'] = $request->desc;
		$arr['sort'] = $request->sort;
		$arr['cat_name'] = DB::table('goods_category')->where('id', $request->cat_id)->value('name');
		//查询该商品品牌的原图路径
		$logo = DB::table('brand')->where('id', $id)->value('logo');
		$arr['logo'] = $logo;
		//检测是否有文件上传
        if($request->hasFile('logo')) {
        	//删掉商品品牌原图
            if(file_exists($logo)) {
                unlink($logo);
            }
            //拼接文件夹路径
            $destinationPath = './Public/upload/brand/'.date('Y').'/'.date('m-d').'/';
            //拼接文件路径
            $fileName = time().rand(100000, 999999);
            //获取上传文件的后缀
            $suffix = $request->file('logo')->getClientOriginalExtension();
            //文件的完整的名称
            $fullName = $fileName.'.'.$suffix;
            //保存文件
            $request->file('logo')->move($destinationPath, $fullName);
            //拼接文件上传之后的路径  
            $arr['logo'] = $destinationPath.$fullName;   
        }
        //更新数据表品牌信息
        $bool = DB::table('brand')->where('id', $id)->update([
        		'name' => $arr['name'],
        		'parent_cat_id' => $arr['parent_cat_id'],
        		'cat_id' => $arr['cat_id'],
        		'url' => $arr['url'],
        		'desc' => $arr['desc'],
        		'logo' => $arr['logo'],
        		'sort' => $arr['sort'],
        		'cat_name' => $arr['cat_name']
        	]);
        //判断是否更新成功
        if($bool){
        	return redirect(url('admin/Goods/brandList'))->with('info','修改成功');
        }else{
        	return back()->with('info', '修改失败');
        }
    }

    /**
     * 删除商品品牌信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del($id)
    {
        //查询该品牌下是否还有商品
        $res = DB::table('goods')->where('brand_id', $id)->get();
        if(count($res)==true){
            //该品牌下还有商品，不能删除
            return back()->with('info', '删除失败，该品牌下还有商品');
        }else{
            //该品牌下没有商品，可以删除
            $logo = DB::table('brand')->where('id', $id)->value('logo');
            $bool = DB::table('brand')->where('id', $id)->delete();
            if($bool){
                //删除该品牌的logo
                if(file_exists($logo)) {
                    unlink($logo);
                }
                return redirect(url('admin/Goods/brandList'))->with('info','删除成功');
            }else{
                return back()->with('info', '删除失败');
            }
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
        //修改品牌列表是否推荐的状态
        if(isset($input['id']) && $input['is_hot']==1){
            $data = ['is_hot' => 0];
            $res = DB::table('brand')->where('id', $input['id'])->update($data);
        }elseif(isset($input['id']) && $input['is_hot']==0){
            $data = ['is_hot' => 1];
            $res = DB::table('brand')->where('id', $input['id'])->update($data);
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
