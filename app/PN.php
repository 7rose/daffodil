<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PN extends Model
{
    protected $table = 'pn'; 
    protected $fillable = ['pn', 'shop', 'buy', 'cart', 'abandon', 'created_by', 'content'];
    //public $timestamps = false;
}
