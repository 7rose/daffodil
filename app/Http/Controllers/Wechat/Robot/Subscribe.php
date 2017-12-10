<?php

namespace App\Http\Controllers\Wechat\Robot;

use App\Wechat;
use App\Card;

use App\Http\Controllers\Wechat\Robot\UpdateUser;
use RestRose\Wechat\Publics\User;
use RestRose\Wechat\Publics\Helpers;
use RestRose\Wechat\Publics\Message;

/**
 * subcribe
 *
 */
class Subscribe 
{
    public $array;

    public $gift_id;
    public $expires_in=604800; # a week

    private $openid;

    // serv
    public function serve () 
    {
        $this->openid = $this->array['FromUserName'];
        $this->countNum();
        $this->welcome();
    }

    // save info to wechat table
    private function countNum () 
    {
        $target = Wechat::where('openid', $this->openid)->first();
        if(!count($target)) $this->setInfo($this->openid);

        if(array_has($this->array, 'EventKey') && $this->array['EventKey'] == true) {
            $new_target = Wechat::where('openid', $this->openid)->first();
            if(count($new_target) && $new_target->new == true){
                $new_target->update(['subscribe_from' => $this->array['EventKey']]);

                $this->sendGift($this->array['EventKey']); # send gift

            }
       }
    }

    // set Info
    private function setInfo () 
    {
        $up = new UpdateUser;
        $up->openid = $this->openid;
        $up->upNow();
    }

    /**
     * send news
     *
     * @param $toUser, $title, $description, $picurl, $url
     */
    public function welcome () 
    {
        $msg = new Message;
        $msg->toUser = $this->array['FromUserName'];
        $msg->title = '好生活, 乐万家!';
        $msg->description = '有奖推荐, 好礼不停, 尽在lwjzc.com';
        $msg->picurl = '/custom/images/welcome.png';
        $msg->url = '/wechat/subscribe';
        $msg->answerNews();
        // $msg->content = 'what the fuck';
        // $msg->answerText();
    }

    // send gift 
    public function sendGift ($key) 
    {
        $array = explode('_', $key);
        $id = end($array);

        if(is_int(intval($id))) {
            if(empty($this->gift_id)) $this->gift_id = 2; # 免费日式卤蛋

            $gift = ['owner_wechat_id' => $id,
                     'card_face_id' => $this->gift_id,
                     'from' => '推荐奖励',
                     'expires_in' => (time() + $this->expires_in)
                    ];
            Card::create($gift);
        }else{
            // echo "fuck";
        }
    }

    //end

}












