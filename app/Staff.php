<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    protected $fillable = ['sn','mobile','mobile_confirmed','org','department','position','name','name_en','gender','password','new','root','admin','locked','hide','visitor','lang','img','content','content_en','created_by'];
    protected $hidden = ['password', 'remember_token'];
}

