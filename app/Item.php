<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = ['sn', 'type', 'title', 'summary', 'img', 'price', 'unit', 'num', 'for_sale', 'show', 'locked', 'created_by', 'content'];
    // protected $hidden = ['remember_token'];
}

           
