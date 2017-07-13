<?php

namespace App\Http\Controllers\Admin\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Libs\Pages\Page;
use Cache;

class GoodsController extends Controller
{

    /**
     * 显示所有的商品信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $where = function ($query) use ($request) {
            //判断是否要检索分类
            if(!empty($request->cat_level_1)){
                if(!empty($request->cat_level_2)){
                    if(!empty($request->cat_id)){
                        $query->where('cat_id', $request->cat_id);
                    }else{
                        $query->where('cat_level_2', $request->cat_level_2);
                    }
                }else{
                    $query->where('cat_level_1', $request->cat_level_1);
                }
            }

            //判断是否要检索品牌
            if (!empty($request->brand_id)) {
                $query->where('brand_id', $request->brand_id);
            }
            //判断是否要检索上架或者下架
            if ($request->is_on_sale == '1') {
                $query->where('is_on_sale', 1);
            } elseif ($request->is_on_sale == '0') {
                $query->where('is_on_sale', 0);
            }
            //判断是否要检索商品是否新品
            if ($request->intro == 'is_new') {
                $query->where('is_new', 1);
            }elseif ($request->intro == 'is_recommend') {   //判断是否要检索商品是否被推荐
                $query->where('is_recommend', 1);
            }
            //判断是否有关键字
            if (!empty($request->keyword)) {
                $query->where('goods_name', 'like', '%' . $request->keyword . '%');
            }
        };
        //查询商品总条数
        $totalRows = DB::table('goods')->where($where)->count();
        //创建一个分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Goods/goodsList');
        //获取所有的商品信息
        $goods = DB::table('goods')->orderBy('goods_id', 'desc')->where($where)->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        //查询商品的分类名称
        foreach ($goods as $k => $v) {
            $goods[$k]->cat_name = DB::table('goods_category')->where('id', $v->cat_id)->value('name');
        }
        //查询所有的商品分类
        $type = DB::table('goods_category')->where('level', 1)->get();
        //查询所有的商品品牌
        $brand = DB::table('brand')->get();
        //查询商品的库存和价格
        //将查询到的商品信息传给模板
        return view('Admin.Goods.goodsList', compact('goods', 'type', 'brand', 'pageObj'));
    }

    /**
     * 显示创建一个商品的表格
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //查询所有的商品一级分类
        $type = DB::table('goods_category')->where('level', 1)->get();
        //查询商品品牌
        $brand = DB::table('brand')->get();
        //获取商品类型
        $goodsType = DB::table('goods_type')->get();
        return view('Admin.Goods.addGoods', compact('type', 'brand', 'goodsType'));
    }

    /**
     * 保存新增的商品信息
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        //验证表单信息
        $this->validate($request, [
            'goods_name' => 'required|unique:goods',
            'cat_id' => 'required', 'cat_id_2' => 'required',
            'cat_id_3' => 'required', 'brand_id' => 'required',
            'shop_price' => 'required', 'cost_price' => 'required',
            'original_img' => 'required', 'market_price' => 'required'
        ], [
            'goods_name.required' => '商品名称不能为空',
            'goods_name.unique' => '已经存在该商品',
            'cat_id.required' => '请选择商品的一级分类',
            'cat_id_2.required' => '请选择商品的二级分类',
            'cat_id_3.required' => '请选择商品的三级分类',
            'brand_id.required' => '请选择商品的品牌类型',
            'shop_price.required' => '请填写商品的本店售价',
            'market_price.required' => '请填写商品的市场价',
            'cost_price.required' => '请填写商品的成本价',
            'original_img.required' => '请上传商品图片'
        ]);
        //将商品的基本信息插到goods表中
        DB::beginTransaction();
        $bool = DB::table('goods')->insert(['goods_name' => $request->goods_name, 'cat_id' => $request->cat_id_3, 'cat_level_1' => $request->cat_id, 'cat_level_2' => $request->cat_id_2, 'brand_id' => $request->brand_id, 'shop_price' => $request->shop_price, 'market_price' => $request->market_price, 'cost_price' => $request->cost_price, 'keywords' => $request->keywords, 'goods_content' => $request->input('content'), 'original_img' => $request->original_img, 'on_time' => time(), 'goods_type' => $request->goods_type, 'spec_type' => $request->spec_type,]);
        //更新该商品的商品货号
        if ($bool) {
            $goods_id = DB::table('goods')->where('goods_name', $request->goods_name)->value('goods_id');
            $bool2 = DB::table('goods')->where('goods_name', $request->goods_name)->update(['goods_sn' => 'TP' . str_pad($goods_id, 6, '0', STR_PAD_LEFT) . str_pad($request->cat_id, 4, '0', STR_PAD_LEFT) . str_pad($request->cat_id_2, 4, '0', STR_PAD_LEFT) . str_pad($request->cat_id_3, 4, '0', STR_PAD_LEFT),]);
        } else {
            $bool2 = false;
        }
        //将商品的属性插到goods_attr表中
        if (!empty($request->attr)) {
            foreach ($request->attr as $k => $v) {
                if (!empty($v)) {
                    $bool3 = DB::table('goods_attr')->insert(['goods_id' => $goods_id, 'attr_id' => $k, 'attr_value' => $v,]);
                }
            }
        } else {
            $bool3 = true;
        }
        //将商品缩略图插到goods_images表中
        if (!empty($request->goods_images)) {
            foreach ($request->goods_images as $k => $v) {
                if (!empty($v)) {
                    $bool4 = DB::table('goods_images')->insert(['goods_id' => $goods_id, 'image_url' => $v,]);
                }
            }
        } else {
            $bool4 = true;
        }
        //将商品规格价钱库存等信息插到spec_goods_price信息表中
        if (!empty($request->price) && !empty($request->store)) {
            foreach ($request->price as $k => $v) {
                $key_name = '';
                $arr = explode('_', $k);
                foreach ($arr as $ka => $va) {
                    $item = DB::table('spec_item')->where('id', $va)->get()->toArray();
                    $itemName = $item[0]->item;
                    $specName = DB::table('spec')->where('id', $item[0]->spec_id)->value('name');
                    $key_name .= $specName . ':' . $itemName . ' ';
                }
                $bool5 = DB::table('spec_goods_price')->insert(['goods_id' => $goods_id, 'key' => $k, 'price' => $v, 'store_count' => $request->store[$k], 'key_name' => $key_name,]);
            }
        } else {
            $bool5 = true;
        }
        //将商品规格图片插到spec_image中
        if (!empty($request->item_img)) {
            foreach ($request->item_img as $k => $v) {
                if (!empty($v)) {
                    $bool6 = DB::table('spec_image')->insert(['goods_id' => $goods_id, 'spec_image_id' => $k, 'src' => $v,]);
                }
            }
        } else {
            $bool6 = true;
        }
        if ($bool == true && $bool2 == true && $bool3 == true && $bool4 == true && $bool5 == true && $bool6 == true) {
            DB::commit();
            return redirect(url('admin/Goods/goodsList'))->with('info', '添加成功');
        } else {
            DB::rollBack();
            return back()->with('info', '添加失败');
        }
    }


    /**
     * 显示修改商品信息的表格
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //查询所有的商品一级分类
        $type = DB::table('goods_category')->where('level', 1)->get();
        //查询商品品牌
        $brand = DB::table('brand')->get();
        //获取商品类型
        $goodsType = DB::table('goods_type')->get();
        //根据商品ID查询该商品的信息
        $goodsInfo = DB::table('goods')->where('goods_id', $id)->get();
        //查询该商品的缩略图
        $goodsImage = DB::table('goods_images')->where('goods_id', $id)->pluck('image_url');
        return view('Admin.Goods.editGoods', compact('type', 'brand', 'goodsType', 'goodsInfo', 'goodsImage'));
    }

    /**
     * 更新商品信息
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        //验证表单信息
        $this->validate($request, ['goods_name' => 'required', 'cat_id' => 'required', 'cat_id_2' => 'required', 'cat_id_3' => 'required', 'brand_id' => 'required', 'shop_price' => 'required', 'cost_price' => 'required', 'original_img' => 'required', 'market_price' => 'required',], ['goods_name.required' => '商品名称不能为空', 'cat_id.required' => '请选择商品的一级分类', 'cat_id_2.required' => '请选择商品的二级分类', 'cat_id_3.required' => '请选择商品的三级分类', 'brand_id.required' => '请选择商品的品牌类型', 'shop_price.required' => '请填写商品的本店售价', 'market_price.required' => '请填写商品的市场价', 'cost_price.required' => '请填写商品的成本价', 'original_img.required' => '请上传商品图片',]);
        //更新goods表
        DB::beginTransaction();
        $bool = DB::table('goods')->where('goods_id', $id)->update(['goods_name' => $request->goods_name, 'cat_id' => $request->cat_id_3, 'cat_level_1' => $request->cat_id, 'cat_level_2' => $request->cat_id_2, 'brand_id' => $request->brand_id, 'shop_price' => $request->shop_price, 'market_price' => $request->market_price, 'cost_price' => $request->cost_price, 'keywords' => $request->keywords, 'goods_content' => $request->input('content'), 'original_img' => $request->original_img, 'on_time' => time(), 'goods_type' => $request->goods_type, 'spec_type' => $request->spec_type,]);
        //更新goods_attr表，先删除原本的记录，再添加新的数据
        $bool2 = DB::table('goods_attr')->where('goods_id', $id)->delete();
        //将商品的属性插到goods_attr表中
        if (!empty($request->attr)) {
            if ($bool) {
                foreach ($request->attr as $k => $v) {
                    if (!empty($v)) {
                        $bool3 = DB::table('goods_attr')->insert(['goods_id' => $id, 'attr_id' => $k, 'attr_value' => $v,]);
                    }
                }
            }
        } else {
            $bool3 = true;
        }
        //更新goods_images表，先删除原本的记录，再添加新的数据
        $bool4 = DB::table('goods_images')->where('goods_id', $id)->delete();
        //将商品缩略图插到goods_images表中
        if (!empty($request->goods_images)) {
            if ($bool) {
                foreach ($request->goods_images as $k => $v) {
                    if (!empty($v)) {
                        $bool5 = DB::table('goods_images')->where('')->insert(['goods_id' => $id, 'image_url' => $v,]);
                    }
                }
            }
        } else {
            $bool5 = true;
        }
        //更新spec_goods_price表，先删除原本的记录，再添加新的数据
        $bool6 = DB::table('spec_goods_price')->where('goods_id', $id)->delete();
        //将商品规格价钱库存等信息插到spec_goods_price信息表中
        if (!empty($request->price) && !empty($request->store)) {
            foreach ($request->price as $k => $v) {
                $key_name = '';
                $arr = explode('_', $k);
                foreach ($arr as $ka => $va) {
                    $item = DB::table('spec_item')->where('id', $va)->get()->toArray();
                    $itemName = $item[0]->item;
                    $specName = DB::table('spec')->where('id', $item[0]->spec_id)->value('name');
                    $key_name .= $specName . ':' . $itemName . ' ';
                }
                $bool7 = DB::table('spec_goods_price')->insert(['goods_id' => $id, 'key' => $k, 'price' => $v, 'store_count' => $request->store[$k], 'key_name' => $key_name,]);
            }
        } else {
            $bool7 = true;
        }
        //更新spec_image表，先删除原本的记录，再添加新的数据
        $bool8 = DB::table('spec_image')->where('goods_id', $id)->delete();
        //将商品规格图片插到spec_image中
        if (!empty($request->item_img)) {
            foreach ($request->item_img as $k => $v) {
                if (!empty($v)) {
                    $bool9 = DB::table('spec_image')->insert(['goods_id' => $id, 'spec_image_id' => $k, 'src' => $v,]);
                }
            }
        } else {
            $bool9 = true;
        }
        if ($bool == true && $bool2 == true && $bool3 == true && $bool4 == true && $bool5 == true && $bool6 == true && $bool7 == true && $bool8 == true && $bool9 == true) {
            DB::commit();
            return redirect(url('admin/Goods/goodsList'))->with('info', '修改成功');
        } else {
            DB::rollBack();
            return back()->with('info', '修改失败');
        }
    }


    /**
     * 显示上传商品图片的弹框
     *
     */
    public function shopUpload()
    {
        return view('Admin.Uploadify.shopUpload');
    }

    /**
     * 显示上传商品缩略图的弹框
     *
     */
    public function upload()
    {
        return view('Admin.Uploadify.upload');
    }

    /**
     * 显示上传商品规格图片的弹框
     *
     */
    public function specUpload()
    {
        return view('Admin.Uploadify.specUpload');
    }

    /**
     * 保存上传的商品图片
     * @param Request $request
     * @return mixed
     */
    public function saveImg(Request $request)
    {
        $res = $request->file('Filedata');
        // return $res;
        if ($res) {
            //拼接文件夹路径
            $destinationPath = './Public/upload/goods/thumb/' . date('Y') . '/' . date('m-d') . '/';
            //拼接文件路径
            $fileName = time() . rand(100000, 999999);
            //获取上传文件的后缀
            $suffix = $res->getClientOriginalExtension();
            //文件的完整的名称
            $fullName = $fileName . '.' . $suffix;
            //保存文件
            $res->move($destinationPath, $fullName);
            $data['state'] = 'SUCCESS';
            $path = $destinationPath . $fullName;
            $data['url'] = trim($path, '.');
        } else {
            $data['state'] = 'FALSE';
            $data['text'] = '上传失败';
        }
        return $data;
    }

    /**
     * 删除上传的图片信息
     *
     */
    public function delupload()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : null;
        $filename = isset($_GET['filename']) ? $_GET['filename'] : null;
        $filename = str_replace('../', '', $filename);
        $filename = trim($filename, '.');
        $filename = trim($filename, '/');
        if ($action == 'del' && !empty($filename)) {
            $size = getimagesize($filename);
            $filetype = explode('/', $size['mime']);
            if ($filetype[0] != 'image') {
                return false;
                exit;
            }
            unlink($filename);
            exit;
        }
    }

    /**
     * 通过ajax选择不同的属性返回该属性下的所有规格
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ajaxGetSpec(Request $request)
    {
        $spec_type = $request->spec_type;
        $goods_id = $request->goods_id ? $request->goods_id : 0;
        $specList = DB::table('spec')->where('type_id', $spec_type)->get();
        foreach ($specList as $k => $v) {
            $specList[$k]->spec_item = DB::table('spec_item')->where('spec_id', $v->id)->get();
            if ($goods_id) {
                // dd($specList[$k]->spec_item);
                foreach ($specList[$k]->spec_item as $k2 => $v2) {
                    // dd($v2);
                    $v2->src = DB::table('spec_image')->where('goods_id', $goods_id)->where('spec_image_id', $v2->id)->value('src');
                }
            }
        }

        $items_id = DB::table('spec_goods_price')->where('goods_id', $goods_id)->get()->toArray();
        // dd($items_id);
        if (!empty($items_id)) {
            $items_ids = array();
            $item_ids = array();
            foreach ($items_id as $k => $v) {
                $items_ids[] = explode('_', $v->key);
            }
            foreach ($items_ids as $k => $v) {
                foreach ($v as $k2 => $v2) {
                    $item_ids[] = (int)$v2;
                }
            }
        } else {
            $item_ids = array();
        }
        // dd($item_ids);

        // dd($specList);
        return view('Admin.Goods.ajaxSpecSelect', compact('specList', 'item_ids'));
    }

    /**
     * 动态获取商品规格输入框 根据不同的数据返回不同的输入框
     */
    public function ajaxGetSpecInput(Request $request)
    {
        $goods_id = $request->goods_id ? $request->goods_id : 0;
        //判断是否有传spec_arr的值过来，没有则随便给它赋值一个数组
        $spec_arr = $request->spec_arr ? $request->spec_arr : ['a', 'b'];
        // dd($spec_arr);
        $str = $this->getSpecInput($goods_id, $spec_arr);
        exit($str);
    }

    /**
     * 获取 规格的 笛卡尔积
     * @param $goods_id 商品 id
     * @param $spec_arr 笛卡尔积
     * @return string 返回表格字符串
     */
    public function getSpecInput($goods_id, $spec_arr)
    {
        // 排序
        // dump($spec_arr);
        foreach ($spec_arr as $k => $v) {
            $spec_arr_sort[$k] = count($v);
        }
        asort($spec_arr_sort);
        // dump($spec_arr_sort);   
        foreach ($spec_arr_sort as $key => $val) {
            // dd( $val);
            $spec_arr2[$key] = $spec_arr[$key];
        }
        // dump($spec_arr2);

        $clo_name = array_keys($spec_arr2);
        // dump($spec_arr2);
        $spec_arr2 = $this->combineDika($spec_arr2); //  获取 规格的 笛卡尔积
        // dump($spec_arr2);
        $spec = DB::table('spec')->select('id', 'name')->get()->toArray(); // 规格表
        // dump($spec);
        $specItem = DB::table('spec_item')->select('id', 'item', 'spec_id')->get()->toArray();//规格项
        // dd($specItem);
        if ($goods_id == true) {
            $keySpecGoodsPrice = DB::table('spec_goods_price')->where('goods_id', $goods_id)->select('key', 'key_name', 'price', 'store_count', 'bar_code')->get()->toArray();//规格项
        }

        $str = "<table class='table table-bordered' id='spec_input_tab'>";
        $str .= "<tr>";
        // 显示第一行的数据
        foreach ($clo_name as $k => $v) {
            foreach ($spec as $sp) {
                if ($sp->id == $v) {
                    $str .= " <td><b>{$sp->name}</b></td>";
                }
            }


        }
        $str .= "<td><b>价格</b></td>
               <td><b>库存</b></td>
               
             </tr>";

        foreach ($specItem as $item) {
            $specItem2[$item->id] = $item;
        }
        // 显示第二行开始
        foreach ($spec_arr2 as $k => $v) {
            // dump($v);
            $str .= "<tr>";
            $item_key_name = array();
            // dump($v);
            foreach ($v as $k2 => $v2) {
                // dump($v2);
                // dump($specItem);
                // dd($spec[$specItem[$v2]->spec_id]);
                $str .= "<td>{$specItem2[$v2]->item}</td>";
                $item_key_name[$v2] = $spec[$specItem2[$v2]->spec_id]->name . ':' . $specItem2[$v2]->item;
            }
            ksort($item_key_name);
            $item_key = implode('_', array_keys($item_key_name));
            $item_name = implode(' ', $item_key_name);
            // dd($keySpecGoodsPrice);
            $price = isset($keySpecGoodsPrice[$k]->price) ? $keySpecGoodsPrice[$k]->price : 0; // 价格默认为0
            $store_count = isset($keySpecGoodsPrice[$k]->store_count) ? $keySpecGoodsPrice[$k]->store_count : 0; //库存默认为0
            // dd($item);
            $str .= "<td><input name='price[$item_key]->price' value='{$price}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";
            $str .= "<td><input name='store[$item_key]->store_count' value='{$store_count}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")'/></td>";
            $str .= "</tr>";
        }
        $str .= "</table>";
        return $str;
    }

    /**
     * ajax获取商品属性输入框选项
     * @param Request $request
     * @return string
     */
    public function getAttrInput(Request $request)
    {
        // dd($request->goods_id);
        $type_id = $request->type_id;
        $goods_id = $request->goods_id;
        $str = '';
        header("Content-type: text/html; charset=utf-8");
        $attributeList = DB::table('goods_attribute')->where('type_id', $type_id)->get()->toArray();
        foreach ($attributeList as $key => $val) {

            $curAttrVal = $this->getGoodsAttrVal(NULL, $goods_id, $val->attr_id);
            //促使他 循环
            if (count($curAttrVal) == 0) $curAttrVal[] = array('goods_attr_id' => '', 'goods_id' => '', 'attr_id' => '', 'attr_value' => '', 'attr_price' => '');
            foreach ($curAttrVal as $k => $v) {
                $str .= "<tr class='attr_{{$val->attr_id}}'>";
                $addDelAttr = '';
                // 单选属性 或者 复选属性
                if ($val->attr_type == 1 || $val->attr_type == 2) {
                    if ($k == 0) $addDelAttr .= "<a onclick='addAttr(this)' href='javascript:void(0);'>[+]</a>&nbsp&nbsp"; else
                        $addDelAttr .= "<a onclick='delAttr(this)' href='javascript:void(0);'>[-]</a>&nbsp&nbsp";
                }

                $str .= "<td>$addDelAttr {$val->attr_name}</td> <td>";

                // 手工录入
                if ($val->attr_input_type == 0) {
                    $tmp = isset($v->attr_value) ? $v->attr_value : '';
                    $str .= "<input type='text' size='40' value='{$tmp}' name='attr[{$val->attr_id}]' />";
                }
                // 从下面的列表中选择（一行代表一个可选值）
                if ($val->attr_input_type == 1) {
                    $str .= "<select name='attr[{$val->attr_id}]'>";
                    $tmp_option_val = explode(PHP_EOL, $val->attr_values);
                    foreach ($tmp_option_val as $k2 => $v2) {
                        // 编辑的时候 有选中值
                        $v2 = preg_replace("/\s/", "", $v2);
                        $tmp = isset($v->attr_value) ? $v->attr_value : '';
                        if ($tmp == $v2) $str .= "<option selected='selected' value='{$v2}'>{$v2}</option>"; else
                            $str .= "<option value='{$v2}'>{$v2}</option>";
                    }
                    $str .= "</select>";
                    //$str .= "属性价格<input type='text' maxlength='10' size='5' value='{$v['attr_price']}' name='attr_price_{$val['attr_id']}[]'>";
                }
                // 多行文本框
                if ($val->attr_input_type == 2) {
                    $str .= "<textarea cols='40' rows='3' name='attr[{$val['attr_id']}][]'>{$v->attr_value}</textarea>";
                    //$str .= "属性价格<input type='text' maxlength='10' size='5' value='{$v['attr_price']}' name='attr_price_{$val['attr_id']}[]'>";
                }
                $str .= "</td></tr>";
                //$str .= "<br/>";
            }

        }
        return $str;
    }

    /**
     * ajax获取商品属性输入框选项
     * @param Request $request
     * @return string
     */
    public function getAttrInput2(Request $request)
    {
        // dd($request->goods_id);
        $type_id = $request->type_id;
        $goods_id = $request->goods_id;
        $str = '';
        header("Content-type: text/html; charset=utf-8");
        $attributeList = DB::table('goods_attribute')->where('type_id', $type_id)->get()->toArray();
        foreach ($attributeList as $key => $val) {

            $curAttrVal = $this->getGoodsAttrVal(NULL, $goods_id, $val->attr_id);
            //促使他 循环
            if (count($curAttrVal) == 0) $curAttrVal[] = array('goods_attr_id' => '', 'goods_id' => '', 'attr_id' => '', 'attr_value' => '', 'attr_price' => '');
            foreach ($curAttrVal as $k => $v) {
                $str .= "<tr class='attr_{{$val->attr_id}}'>";
                $addDelAttr = '';
                // 单选属性 或者 复选属性
                if ($val->attr_type == 1 || $val->attr_type == 2) {
                    if ($k == 0) $addDelAttr .= "<a onclick='addAttr(this)' href='javascript:void(0);'>[+]</a>&nbsp&nbsp"; else
                        $addDelAttr .= "<a onclick='delAttr(this)' href='javascript:void(0);'>[-]</a>&nbsp&nbsp";
                }

                $str .= "<td>$addDelAttr {$val->attr_name}</td> <td>";

                // 手工录入
                if ($val->attr_input_type == 0) {
                    @$str .= "<input type='text' size='40' value='{$v->attr_value}' name='attr[{$val->attr_id}]' />";
                }
                // 从下面的列表中选择（一行代表一个可选值）
                if ($val->attr_input_type == 1) {
                    $str .= "<select name='attr[{$val->attr_id}]'>";
                    $tmp_option_val = explode(PHP_EOL, $val->attr_values);
                    foreach ($tmp_option_val as $k2 => $v2) {
                        // 编辑的时候 有选中值
                        $v2 = preg_replace("/\s/", "", $v2);

                        if (@$v->attr_value == $v2) $str .= "<option selected='selected' value='{$v2}'>{$v2}</option>"; else
                            $str .= "<option value='{$v2}'>{$v2}</option>";
                    }
                    $str .= "</select>";
                    //$str .= "属性价格<input type='text' maxlength='10' size='5' value='{$v['attr_price']}' name='attr_price_{$val['attr_id']}[]'>";
                }
                // 多行文本框
                if ($val->attr_input_type == 2) {
                    $str .= "<textarea cols='40' rows='3' name='attr[{$val['attr_id']}][]'>{$v->attr_value}</textarea>";
                    //$str .= "属性价格<input type='text' maxlength='10' size='5' value='{$v['attr_price']}' name='attr_price_{$val['attr_id']}[]'>";
                }
                $str .= "</td></tr>";
                //$str .= "<br/>";
            }

        }
        return $str;
    }

    /**
     * 获取 tp_goods_attr 表中指定 goods_id  指定 attr_id  或者 指定 goods_attr_id 的值 可是字符串 可是数组
     * @param int $goods_attr_id tp_goods_attr表id
     * @param int $goods_id 商品id
     * @param int $attr_id 商品属性id
     * @return array 返回数组
     */
    public function getGoodsAttrVal($goods_attr_id = 0, $goods_id = 0, $attr_id = 0)
    {
        if ($goods_attr_id > 0) return DB::table('goods_attr')->where('goods_attr_id', $goods_attr_id)->get()->toArray();
        if ($goods_id > 0 && $attr_id > 0) return DB::table('goods_attr')->where('goods_id', $goods_id)->where('attr_id', $attr_id)->get()->toArray();
    }


    /**
     * 多个数组的笛卡尔积
     * @return array
     */
    function combineDika()
    {
        $data = func_get_args();
        $data = current($data);
        $cnt = count($data);
        $result = array();
        $arr1 = array_shift($data);
        foreach ($arr1 as $key => $item) {
            $result[] = array($item);
        }

        foreach ($data as $key => $item) {
            $result = $this->combineArray($result, $item);
        }
        return $result;
    }


    /**
     * 两个数组的笛卡尔积
     * @param $arr1
     * @param $arr2
     * @return array
     */
    public function combineArray($arr1, $arr2)
    {
        $result = array();
        foreach ($arr1 as $item1) {
            foreach ($arr2 as $item2) {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function del($id)
    {
        DB::transaction(function () use ($id) {
            DB::table('goods')->where('goods_id', $id)->delete();
            DB::table('goods_attr')->where('goods_id', $id)->delete();
            DB::table('goods_collect')->where('goods_id', $id)->delete();
            DB::table('goods_images')->where('goods_id', $id)->delete();
            DB::table('spec_goods_price')->where('goods_id', $id)->delete();
            DB::table('spec_image')->where('goods_id', $id)->delete();
        });
        return redirect(url('admin/Goods/goodsList'))->with('info', '删除成功');
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
        //修改商品是否上架的状态
        if(isset($input['is_on_sale']) && $input['is_on_sale']==1){
            $data = ['is_on_sale' => 0];
            $res = DB::table('goods')->where('goods_id', $input['goods_id'])->update($data);
        }elseif(isset($input['is_on_sale']) && $input['is_on_sale']==0){
            $data = ['is_on_sale' => 1];
            $res = DB::table('goods')->where('goods_id', $input['goods_id'])->update($data);
        }
        //修改商品是否推荐的状态
        if(isset($input['is_recommend']) && $input['is_recommend']== 1){
            $data = ['is_recommend' => 0];
            $res = DB::table('goods')->where('goods_id', $input['goods_id'])->update($data);
        }elseif(isset($input['is_recommend']) && $input['is_recommend']==0){
            $data = ['is_recommend' => 1];
            $res = DB::table('goods')->where('goods_id', $input['goods_id'])->update($data);
        }
        //修改商品是否新品的状态
        if(isset($input['is_new']) && $input['is_new']== 1){
            $data = ['is_new' => 0];
            $res = DB::table('goods')->where('goods_id', $input['goods_id'])->update($data);
        }elseif(isset($input['is_new']) && $input['is_new']==0){
            $data = ['is_new' => 1];
            $res = DB::table('goods')->where('goods_id', $input['goods_id'])->update($data);
        }
        //修改商品是否热卖的状态
        if(isset($input['is_hot']) && $input['is_hot']== 1){
            $data = ['is_hot' => 0];
            $res = DB::table('goods')->where('goods_id', $input['goods_id'])->update($data);
        }elseif(isset($input['is_hot']) && $input['is_hot']==0){
            $data = ['is_hot' => 1];
            $res = DB::table('goods')->where('goods_id', $input['goods_id'])->update($data);
        }
        if(!empty($res)){
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
