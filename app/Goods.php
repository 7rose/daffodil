<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods'; 
    protected $fillable = ['sn', 'fashion', 'price', 'type', 'gender', 'name', 'name_en', 'gold', 'gold_level', 'stone', 'stone_color', 'other', 'img', 'hide', 'locked', 'created_by'];
    //public $timestamps = false;
}
