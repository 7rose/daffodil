<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServerKey extends Model
{
    protected $table = 'server_keys';
    protected $fillable = ['key', 'value'];
}
