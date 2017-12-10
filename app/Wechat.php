<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wechat extends Model
{
    protected $table = 'wechat';
    protected $fillable = ['staff_id', 'uuid', 'openid', 'subscribe', 'nickname', 'sex', 'language', 'city', 'province', 'country', 'headimgurl', 'subscribe_time', 'unionid', 'remark', 'groupid', 'tagid_list', 'qrcode_ticket', 'qrcode_url', 'qrcode_seconds', 'subscribe_from','privilege', 'new', 'access_token', 'expires_in', 'refresh_token', 'refresh_token_expires_in'];
    protected $hidden = ['remember_token'];
}







