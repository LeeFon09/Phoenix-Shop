<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 广告位
 * @package App\Http\Controllers\Home
 */
class AdController extends Controller
{

    public function ad()
    {
        //select * from __PREFIX__ad where position_id = 1 limit 1
        $ad = DB::table("ad")->where("pid",1)->limit(1)->first();
//        dd($ad);
//        return $ad;
        $cateList = [];
//        return view("Home.Public.header", compact("ad","cateList"));
    }

}
