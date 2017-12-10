<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'name_en', 'type', 'content', 'ca', 'sn', 'shop', 'state', 'price', 'create_by', 'img'];
}
