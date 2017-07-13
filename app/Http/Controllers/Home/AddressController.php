<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * 设置为默认地址
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addressSetDefault($id)
    {
        DB::transaction(function () use ($id){
            $addr = DB::table('user_address')->where(['user_id'=>session('user')->user_id,'is_default'=>1])->first();
            if(!empty($addr)){
                DB::table('user_address')->where(['user_id'=>session('user')->user_id,'is_default'=>1])->update(['is_default'=>0]);
            }
            DB::table('user_address')->where('address_id',$id)->update(['is_default'=>1]);
        });

        return redirect()->action('Home\AddressController@index');
    }
    /**
     * 显示收货地址页面
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = DB::table('user_address')->where('user_id',session('user')->user_id)
            ->orderBy('is_default','desc')->get();
//        dd($lists);
        return view('Home.User.addressMgr',compact('lists'));
    }

    /**
     * 添加收货地址的跳转
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $prolist = DB::table('region')->where(['level'=>1,'parent_id'=>0])->get();
        return view('Home.User.addressAdd',compact('prolist'));
    }

    /**
     * 保存新添加的收货地址
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jsonArr = json_decode($request->input('data'),true);
        $jsonArr['user_id']=session('user')->user_id;
//        dd($jsonArr);
        try {
            DB::table('user_address')->insert($jsonArr);
        } catch (Exception $e) {
            response()->json(["static"=>1,"info"=>"保存失败，请联系管理员！"]);
        }
        return response()->json(["static"=>0,"info"=>"保存成功！"]);
    }

    /**
     * 修改选择的收货地址
     * @param  int  $id address_id,收货地址id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $address = DB::table('user_address')->where('address_id',$id)->first();
        $prolist = DB::table('region')->where(['level'=>1,'parent_id'=>0])->get();
        $citylist = DB::table('region')->where('parent_id',$address->province)->get();
        $arealist = DB::table('region')->where('parent_id',$address->city)->get();
        $townlist = DB::table('region')->where('parent_id',$address->area)->get();
        return view('Home.User.addressEdit',compact('prolist','address','citylist','arealist','townlist'));
    }

    /**
     * 保存修改过的收货地址
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id address_id,收货地址id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $jsonArr = json_decode($request->input('data'),true);
        $jsonArr['user_id']=session('user')->user_id;
//        dd($jsonArr);
        try {
            DB::table('user_address')->where('address_id',$id)->update($jsonArr);
        } catch (Exception $e) {
            response()->json(["static"=>1,"info"=>"保存失败，请联系管理员！"]);
        }
        return response()->json(["static"=>0,"info"=>"保存成功！"]);
    }
    /**
     * 删除用户收货地址
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        DB::table('user_address')->where('address_id',$id)->delete();
        return redirect()->action('Home\AddressController@index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    /**
     * Ajax获取城市列表
     * @param $pid
     */
    public function getCities($pid)
    {
        $citylist = DB::table('region')->where('parent_id',$pid)->get();
        $str = '';
        foreach ($citylist as $city){
            $str .= "<option value='{$city->id}'>{$city->name}</option>";
        }
//        dd($str);
        echo $str;
    }

    /**
     * Ajax获取地区列表
     * @param $pid
     */
    public function getAreas($pid)
    {
        $arealist = DB::table('region')->where(['parent_id'=>$pid,'is_show'=>1])->get();
        $str = '';
        foreach ($arealist as $area){
            $str .= "<option value='{$area->id}'>{$area->name}</option>";
        }
//        dd($str);
        echo $str;
    }

    /**
     * Ajax获取乡镇街道列表
     * @param $pid
     */
    public function getTowns($pid)
    {
        $townlist = DB::table('region')->where(['parent_id'=>$pid])->get();
        $str = '';
        foreach ($townlist as $town){
            $str .= "<option value='{$town->id}'>{$town->name}</option>";
        }
//        dd($str);
        echo $str;
    }
}
