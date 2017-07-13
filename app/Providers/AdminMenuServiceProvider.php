<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class AdminMenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        //后台页面左侧菜单栏
        View::composer("Admin.Public.left", function($view) {
            $arr = DB::table('admin_menu')->where('level', 1)->get()->toArray();
            $menuArr = [];
            foreach($arr as $k=>$v){
                $menuArr[$v->key_word]['name'] = $v->name;
                $menuArr[$v->key_word]['icon'] = $v->icon;
                $data = DB::table('admin_menu')->where('pid', $v->id)->get()->toArray();
                foreach($data as $m=>$n){
                    $menuArr[$v->key_word]['sub_menu'][$m]['name'] = $n->name;
                    $menuArr[$v->key_word]['sub_menu'][$m]['act'] = $n->act;
                    $menuArr[$v->key_word]['sub_menu'][$m]['control'] = $n->control;
                }
            }
            $view->with("menu", $menuArr);
        });

        //后台面包屑
        View::composer("Admin.Public.breadcrumb", function ($view) {
            $arr = DB::table('admin_menu')->where('level', 1)->get();
            $menus = \Request::path();
            $menusArr = explode('/', $menus);
            if (count($menusArr) >= 3) {
                $control = $menusArr[1];
                $act = $menusArr[2];
                $data = [
                    ['act', '=', $act],
                    ['control', '=', $control],
                ];
                $son = DB::table('admin_menu')->where($data)->first();
                if(!empty($son)){
                    $parent = DB::table('admin_menu')->where('id', $son->pid)->first();
                    $menus = [
                        'parent' => $parent->name,
                        'son' => $son->name,
                        'son_url'=> 'admin/'.$son->control.'/'.$son->act,
                    ];
                    $view->with("menu", $menus);
                }
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
