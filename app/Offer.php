<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers'; 
    protected $fillable = ['goods_sn', 'order_pn', 'order_buy_pn', 'sn', 'shop', 'weight', 'gold_weight', 'stone_weight', 'ca', 'other', 'content', 'content_en', 'hide', 'locked', 'for_sale', 'in_service', 'need_label', 'sold', 'sold_price','sold_by', 'sold_time','created_by'];
    //public $timestamps = false;
}


