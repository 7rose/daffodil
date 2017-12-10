<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use Session;
use Cookie;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Wechat;
use RestRose\Wechat\Publics\Message;
use RestRose\Wechat\Publics\User;
use RestRose\Wechat\Publics\Qrcode;
use RestRose\Wechat\Publics\Helpers;
use RestRose\Wechat\Publics\Menu;

class WechatController extends Controller
{
    /**
     * create menu
     *
     */
    public function createMenu ()
    {
        $array = ['button' => [ ['type' => 'view', 'name' => '时鲜特产', 'url'=>'https://lwjzc.com'],
                                ['type' => 'view', 'name' => '有奖推荐', 'url'=>'https://lwjzc.com/wechat/subscribe'],
                                ['type' => 'view', 'name' => '应用中心', 'url'=>'https://lwjzc.com/panel']
                 ]];
        $menu = new Menu;
        $menu->createMenu($array);
    }

    /**
     * qrcode for subscribe
     *
     */
    public function qrcode ()
    {
        if(Session::has('openid')) {
            return view('subscribe')->with('url', $this->getUrl(Session::get('openid')));
        } else {
            return view('subscribe')->with('url', $this->onlyQrcode('web'));
        }
    }

    // only get qrcode 
    private function onlyQrcode ($scene_str) 
    {
        $qrcode = new Qrcode;
        $qrcode->scene_str = $scene_str;
        $qrcode->expire_seconds = 300;
        $resault_array = $qrcode->createQrcode();
        return $resault_array['url'];
    }

    // get url for qrcode
    public function getUrl ($openid)
    {
        if($this->urlAvailable($openid)) {
            return $this->urlAvailable($openid);
        } else {
            return $this->getQrcode ($openid);
        }
    }

    // expired
    private function urlAvailable ($openid) 
    {
        $available_url = Wechat::where('qrcode_url', '<>', '')
                              ->where('qrcode_ticket', '<>', '')
                              ->where('qrcode_seconds', '>', time())
                              ->where('openid', $openid)
                              ->first();
        return count($available_url) ? $available_url->qrcode_url : false;
    }

    // get qrcode
    public function getQrcode ($openid)
    {
        $me = Wechat::where('openid', $openid)->first();
        $scene_id = $me->id;

        $qrcode = new Qrcode;
        $qrcode->scene_id = $scene_id;
        $resault_array = $qrcode->createQrcode();

        // return view('subscribe')->with('url', $resault_array['url']);
        $updates = ['qrcode_ticket'=>$resault_array['ticket'] , 'qrcode_url'=>$resault_array['url'] , 'qrcode_seconds'=> (time()+604800-300) ];

        Wechat::find($scene_id)->update($updates);

        return $resault_array['url'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function event()
    {
        $xml = file_get_contents('php://input');
        $message = new Message;
        $array = $message->gate($xml);

        // Cache::put('xml', $xml, 5);

        $this->checkError($array);
        $this->setSessionAndCookie($array['FromUserName']);

        if($array['MsgType'] == 'event') $this->eventGate($array);
        // if(array_has($array, 'MsgType')) $this->messageGate($array);
    }

    /**
     * check Error
     *
     */
    private function checkError ($array)
    {
        if(!array_has($array, 'MsgType')) abort(400); 
    }

    /**
     * event gate
     *
     */
    public function eventGate($array)
    {
        switch ($array['Event']) {
            case 'subscribe':
                $this->subscribe($array);
                break;

            case 'unsubscribe':
                $this->unsubscribe($array);
                break;

            case 'SCAN':
                # code...
                break;

            case 'LOCATION':
                # code...
                break;
            
            default:
                # msage
                break;
        }
    }

    /**
     * subscribe
     *
     */
    public function subscribe($array)
    {
        if(array_has($array, 'FromUserName')) $this->saveInfo($array);
    }

    /**
     * unsubscribe
     *
     */
    public function unsubscribe($array)
    {
        $this->clearSessionAndCookie();
    }

    // has event id or str


    // save info
    public function saveInfo($array)
    {
        $user = new User;
        $user_info = $user->getInfo($array['FromUserName']);

        if(!$this->exits($array) && $this->hasEventKey($array)) {
            $user_info = array_add($user_info, 'subscribe_from', $array['EventKey']);
        };

        array_forget($user_info, 'subscribe');

        $helper = new Helpers;
        $user_info['tagid_list'] = $helper->array2JSON($user_info['tagid_list']);

        Wechat::updateOrCreate(['openid' => $user_info['openid']], $user_info);

        // $this->setSessionAndCookie ($user_info['openid']); 

    }

    // has event key
    private function hasEventKey ($array) 
    {
        return !array_has($array, 'EventKey') || $array['EventKey'] == '' ? false : $array['EventKey'];
    }

    // exits
    private function exits ($array)
    {
        $record = Wechat::where('openid', $array['FromUserName'])->first();
        return !count($record) ? false : true;
    }

    // set session & cookie
    private function setSessionAndCookie ($openid) 
    {
        if(!Session::has('openid')) Session::put('openid', $openid);
        // Cookie::queue('openid', $openid, 604800);
        if (!Cookie::has('openid')) Cookie::forever('openid', $openid);
    }

    // clear session & cookie
    private function clearSessionAndCookie ()
    {
        Session::flush();
        if (Cookie::has('openid')) Cookie::queue('openid', '', -1);
    }

    // end

}