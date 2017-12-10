<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'card';
    protected $fillable = ['owner_wechat_id', 'card_face_id', 'num', 'from', 'expires_in', 'wechat_qrcode_url', 'locked', 'signature'];
}