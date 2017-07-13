<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Libs\Pages\Page;

class AdController extends Controller
{
    /**
     * 广告列表页的显示及查询操作
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adList(Request $request)
    {
        //查询广告的总条数
        $totalRows = count(DB::table('ad')->get());
        //实例化分页对象
        $pageObj = new Page($totalRows, $request, 'admin/Ad/adList');
        //根据条件查询广告
        $list = DB::table('ad')->where(function ($query) use ($request, &$totalRows, &$pageObj) {
            //根据广告名称查询
            if (!empty($request->keyword)) {
                $query->where('ad_name', 'like', '%' . $request->keyword . '%');
                //查询条数
                $totalRows = count(DB::table('ad')->where('ad_name', 'like', '%' . $request->keyword . '%')->get());
                //实例化分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Ad/adList', 20, 'p', ['keyword' => $request->keyword]);
            }
            //根据广告位置查询
            if ($request->pid > 0) {
                $query->where('pid', '=', $request->pid);
                //查询条数
                $totalRows = count(DB::table('ad')->where('pid', '=', $request->pid)->get());
                //实例化分页对象
                $pageObj = new Page($totalRows, $request, 'admin/Ad/adList', 20, 'p', ['pid' => $request->pid]);
            }
        })->orderBy('ad_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        foreach ($list as $k => $v) {
            $list[$k]->position_name = DB::table('ad_position')->where('position_id', $v->pid)->value('position_name');
        }
        $ad_position_list = DB::table('ad_position')->get();
        return view('Admin.Ad.adList', compact('list', 'ad_position_list', 'pageObj'));
    }

    /**
     * 广告位置页的显示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function positionList(Request $request)
    {
        $count = DB::table('ad_position')->count();
        $pageObj = new Page($count,$request,"admin/Ad/positionList");//分页类实例化
        $list = DB::table('ad_position')->orderBy('position_id', 'desc')->offset($pageObj->firstRow)->limit($pageObj->listRows)->get();
        return view('admin.Ad.positionList', compact('list', 'pageObj'));
    }

    /**
     * 新增广告页面的显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ad()
    {
        $position = DB::table('ad_position')->get();
        return view('admin.Ad.ad', compact('position'));
    }

    /**
     * 处理添加广告及删除广告的操作
     *
     * @param Request $request
     * @return array
     */
    public function adHandle(Request $request)
    {
        //添加广告的操作
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $arr = [];
            if ($input['begin']) {
                $begin = strtotime($input['begin']);
                $arr['start_time'] = $begin;
            }
            if ($input['end']) {
                $end = strtotime($input['end']);
                $arr['end_time'] = $end;
            }
            if ($input['ad_name']) {
                $arr['ad_name'] = $input['ad_name'];
            }
            if (is_numeric($input['media_type'])) {
                $arr['media_type'] = $input['media_type'];
            }
            if ($input['pid']) {
                $arr['pid'] = $input['pid'];
            }
            if ($input['ad_link']) {
                $arr['ad_link'] = $input['ad_link'];
            }
            if ($input['bgcolor']) {
                $arr['bgcolor'] = $input['bgcolor'];
            }
            if ($input['orderby']) {
                $arr['orderby'] = $input['orderby'];
            }
            if ($request->hasFile('ad_code')) {
                $file = Input::file('ad_code');
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
                    $fileDir = '/Public/upload/ad/' . $year . '/' . $date . '/';
                    $path = $file->move(public_path() . $fileDir, $newName);            //文件保存
                    $arr['ad_code'] = $fileDir . $newName;
                }
            }
            $res = DB::table('ad')->insert($arr);
            if ($res) {
                return redirect('admin/Ad/adList');
            } else {
                return back();
            }
        }
        //删除广告的操作
        if ($request->isMethod('delete')) {
            $input = $request->except('_token');
            $ad_id = $input['ad_id'];
            $res = DB::table('ad')->where('ad_id', '=', $ad_id)->delete();
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
     * 编辑广告页面的显示及其处理编辑广告的操作
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        //编辑广告页面的显示
        if ($request->isMethod('get')) {
            $list = DB::table('ad')->where('ad_id', '=', $id)->first();
            $position = DB::table('ad_position')->get();
            return view('admin.Ad.edit', compact('list', 'position'));
        }
        //处理编辑广告的操作
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $begin = strtotime($input['begin']);
            $end = strtotime($input['end']);
            $data = [
                'ad_name' => $input['ad_name'],
                'media_type' => $input['media_type'],
                'pid' => $input['pid'],
                'ad_link' => $input['ad_link'],
                'bgcolor' => $input['bgcolor'],
                'orderby' => $input['orderby'],
                'start_time' => $begin,
                'end_time' => $end
            ];
            if ($request->hasFile('ad_code')) {
                $file = Input::file('ad_code');
                //检验一下上传的文件是否有效.
                if ($file->isValid()) {
                    $clientName = $file->getClientOriginalName();        //文件原始名称
                    $tmpName = $file->getFileName();                     //文件名称
                    $realPath = $file->getRealPath();                    //文件路径
                    $extension = $file->getClientOriginalExtension();    //文件扩展名
                    $size = $file->getSize();                            //文件大小
                    $mime = $file->getMimeType();                        //文件MIME类型
                    $year = date('Y', time());
                    $date = date('m-d', time());
                    $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $extension;
                    $fileDir = '/Public/upload/ad/' . $year . '/' . $date . '/';
                    $path = $file->move(public_path() . $fileDir, $newName);            //文件保存
                    $data['ad_code'] = $fileDir . $newName;
                }
            }
            $res = DB::table('ad')->where('ad_id', $input['ad_id'])->update($data);
            if ($res) {
                return redirect('admin/Ad/adList');
            } else {
                return back();
            }
        }
    }

    /**
     * ajax异步更新广告排序的操作
     *
     * @return array
     */
    public function changeOrder()
    {
        $input = Input::all();
        $ad_id = $input['ad_id'];
        $ad_order = $input['ad_order'];
        $arr = [
            'orderby' => $ad_order,
        ];
        $res = DB::table('ad')->where('ad_id', $ad_id)->update($arr);
        if ($res) {
            $data = [
                'status' => 1,
                'msg' => '广告排序更新成功！'
            ];
        } else {
            $data = [
                'status' => 0,
                'msg' => '广告排序更新失败！'
            ];
        }
        return $data;
    }

    /**
     * 新增广告位页面的显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function position()
    {
        return view('admin.Ad.position');
    }

    /**
     * 处理新增广告位的操作
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function positionHandle(Request $request)
    {
        if($request->isMethod('post')){
            $input = $request->except('_token');
            $arr = [];
            if($input['position_name']){
                $arr['position_name'] = $input['position_name'];
            }
            if($input['ad_width']){
                $arr['ad_width'] = $input['ad_width'];
            }
            if($input['ad_height']){
                $arr['ad_height'] = $input['ad_height'];
            }
            if($input['position_desc']){
                $arr['position_desc'] = $input['position_desc'];
            }
            if(is_numeric($input['is_open'])){
                $arr['is_open'] = $input['is_open'];
            }
            $res = DB::table('ad_position')->insert($arr);
            if ($res) {
                return redirect('admin/Ad/positionList');
            } else {
                return back();
            }
        }
    }

    /**
     * 编辑广告位页面的显示及其处理编辑广告位的操作
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function positionEdit(Request $request, $id)
    {
        //编辑广告位的展示
        if ($request->isMethod('get')) {
            $list = DB::table('ad_position')->where('position_id', $id)->first();
            return view('admin.Ad.positionEdit', compact('list'));
        }
        //处理编辑广告位的操作
        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $arr = [
                'position_name' => $input['position_name'],
                'ad_width' => $input['ad_width'],
                'ad_height' => $input['ad_height'],
                'position_desc' => $input['position_desc'],
                'is_open' => $input['is_open'],
            ];
            $res = DB::table('ad_position')->where('position_id', $input['position_id'])->update($arr);
            if ($res) {
                return redirect('admin/Ad/positionList');
            } else {
                return back();
            }
        }
    }

    /**
     * 查看广告页面的显示
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function positionShow(Request $request, $id)
    {
        $list = DB::table('ad')->join('ad_position', 'ad.pid', '=', 'ad_position.position_id')->where('ad_position.position_id', $id)->orderBy('ad_id', 'desc ')->select('ad.ad_id', 'ad.ad_name', 'ad.ad_code', 'ad.ad_link', 'ad.target', 'ad.enabled', 'ad.orderby', 'ad_position.position_name')->get();
        $ad_position_list = DB::table('ad_position')->get();
        $good_category = DB::table('goods_category')->where('level', '=', 1)->get();
        return view('admin.Ad.adList', compact('list', 'ad_position_list', 'good_category'));
    }
}
