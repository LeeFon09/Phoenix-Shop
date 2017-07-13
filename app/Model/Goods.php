<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods';
    protected $primaryKey= "goods_id";
    public $timestamps = false;

//    public function goodsCategory()
//    {
//        return $this->hasOne("App/Model/GoodsCategory");
//    }
}
