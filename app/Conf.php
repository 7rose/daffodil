<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conf extends Model
{
    protected $table = 'config'; 
    //protected $fillable = ['parent_id', 'level', 'name', 'name_en', 'org', 'for_org', 'independent', 'allow_admin', 'allow_root', 'allow_master', 'is_shop', 'is_supplier', 'is_customer', 'locked', 'hide', 'order', 'extra', 'content', 'content_en', 'created_by'];
    public $timestamps = false;
}
