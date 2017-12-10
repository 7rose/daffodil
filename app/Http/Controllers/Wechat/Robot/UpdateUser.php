<?php

namespace App\Http\Controllers\Wechat\Robot;

use App\Wechat;

use RestRose\Wechat\Publics\User;
use RestRose\Wechat\Publics\Helpers;

/**
 * subcribe
 *
 */
class UpdateUser 
{
    public $openid;
    public $seconds=604800; # a week

    // serv
    public function up () 
    {
        $target = Wechat::where('openid', $this->openid)->first();
        if(count($target)) {
            $int_updated = $target->updated_at->timestamp;
            if(($int_updated + $this->seconds) < time()) $this->upNow();
        }
    }

    // do
    public function upNow () 
    {
        $user = new User;
        $user_info = $user->getInfo($this->openid);
        // array_forget($user_info, 'subscribe');

        $helper = new Helpers;
        $user_info['tagid_list'] = $helper->array2JSON($user_info['tagid_list']);
        Wechat::updateOrCreate(['openid' => $user_info['openid']], $user_info);
    }

    //end
}












