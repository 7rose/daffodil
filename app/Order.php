<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders'; 
    protected $fillable = ['goods_id', 'pn_id', 'num'];
    public $timestamps = false;
}

