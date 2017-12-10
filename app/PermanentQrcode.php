<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermanentQrcode extends Model
{
    protected $table = 'wechat_permanent_qrcode';
    protected $fillable = ['staff_id', 'label', 'ticket', 'url', 'avaliable'];
    // protected $hidden = ['remember_token'];
}

