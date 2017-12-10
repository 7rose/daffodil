<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;

use RestRose\Wechat\Publics\Helpers;

// robots
use App\Http\Controllers\Wechat\Robot\UpdateUser;
use App\Http\Controllers\Wechat\Robot\Subscribe;
use App\Http\Controllers\Wechat\Robot\Unsubscribe;
// use App\Http\Controllers\Wechat\Robot\Scan;

/**
 * robot
 *
 *
 */
class RobotController extends Controller
{
    private $array_post;

    /**
     * ready to serve
     *
     */
    public function serve () 
    {
        $xml = file_get_contents('php://input');
        $message = new Helpers;
        $array = $message->xml2Array($xml);
        $this->array_post = $array;
        $this->checkError();
        $this->updateUserInfo(); # update user info
        $this->gate();
    }

    // update user info 
    public function updateUserInfo ()
    {
        $up = new UpdateUser;
        $up->openid = $this->array_post['FromUserName'];
        // $up->seconds = 302400; # default a week
        $up->up();
    }


    // check error
    private function checkError ()
    {
        if(!array_has($this->array_post, 'MsgType')) abort(400); 
    }

    // gate
    private function gate () 
    {
        $msg_type = $this->array_post['MsgType'];

        switch ($msg_type) {
            case 'event':
                $this->eventGate();
                break;

            // case 'text':
            //     # code...
            //     break;

            // case 'image':
            //     # code...
            //     break;

            // case 'voice':
            //     # code...
            //     break;

            // case 'video':
            //     # code...
            //     break;

            // case 'shortvideo':
            //     # code...
            //     break;

            // case 'location':
            //     # code...
            //     break;

            // case 'link':
            //     # code...
            //     break;

            default:
                echo "success"; # answer wechat server
                break;
        }
    }

    /**
     * event gate
     *
     */
    public function eventGate()
    {
        $event = $this->array_post['Event'];
        switch ($event) {
            case 'subscribe':
                $robot = new Subscribe;
                $robot->array = $this->array_post;
                $robot->serve();
                break;

            case 'unsubscribe':
                $robot = new Unsubscribe;
                $robot->openid = $this->array_post['FromUserName'];
                $robot->un();
                break;

            // case 'SCAN':
            //     # ..
            //     break;

            // case 'LOCATION':
            //     # code...
            //     break;
            
            default:
                echo "success"; # answer wechat server
                break;
        }
    }

    public function test ()
    {
        $array_post = ['FromUserName' => 'ozgC81CU0k-nvsLejTCFq0pzJrGU', 'EventKey'=> 'fuck'];
        // $array= ['FromUserName' => 'ozgC81CU0k-nvsLejTCFq0pzJrGU', 'Event'=> 'unsubscribe'];
        // $this->updateUserInfo($openid);
        // $robot = new UpdateUser;
        // $robot->openid = $array['FromUserName'];
        // $robot->up();
        $a = new Subscribe;
        $a->array = $array_post;
        $a->welcome();
    }

    //end

}












