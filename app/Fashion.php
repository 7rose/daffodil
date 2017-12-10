<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fashion extends Model
{
    protected $table = 'fashions'; 
    protected $fillable = ['set', 'set_en', 'name', 'name_en', 'type', 'gender', 'locked', 'hide', 'img', 'content', 'content_en', 'created_by'];
    //public $timestamps = false;
}

