<?php

namespace App\Http\Controllers\Wechat\Robot;

use App\Wechat;

/**
 * subcribe
 *
 */
class Unsubscribe 
{
    public $openid;

    // save info to wechat table
    public function un () 
    {
        $target = Wechat::where('openid', $this->openid)->first();
        if(count($target)) $target->update(['new' => false]);

        echo "success"; # answer wechat server
    }
}