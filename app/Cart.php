<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = ['goods_id', 'num', 'cart', 'buy','content', 'created_by'];
    // protected $hidden = ['password', 'remember_token'];
}




