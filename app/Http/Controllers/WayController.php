<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;
use Cache;
use Config;

use RestRose\Wechat\Enterprise\Api;
use RestRose\Wechat\Publics\Api as Papi;
use RestRose\Water\CropAvatar;

class WayController extends Controller
{
    /**
     * Wechat Callback settings [Enterprise]
     *
     */
    public function wechatCallbackEnterprise()
    {
        $w = new Api;
        echo $w->openCallbackMode();
    }

    /**
     * Wechat Callback settings [Public]
     *
     */
    public function wechatCallbackPublic()
    {
        $w = new Papi;
        if($w->ca()) echo $w->ca();
    }

    /**
     * Auto update From Github
     *
     */
    public function git(){
    	if(!Config::has('daffodil') || !Config::get('daffodil')['service_path']) return 'daffodil.php Missing';
    	$path = Config::get('daffodil')['service_path'];
    	$key = Config::get('daffodil')['key'];

    	$github_signature = @$_SERVER['HTTP_X_HUB_SIGNATURE'];
        $payload = file_get_contents('php://input');

        $arr = explode('=', $github_signature);
        $algo = $arr[0];
        $signature = $arr[1];

        $payload_hash = hash_hmac($algo, $payload, $key);
        if($payload_hash == $signature){
            shell_exec('cd '.$path);
            shell_exec('/usr/bin/git pull');
            return 200;
        }else{
           return 'invalid key!'; 
        }  
    }

    /**
     * clearCache
     *
     */
    public function clearCache(){
        Cache::flush();
    }

    /**
     * test
     *
     */
    public function test()
    {
        $path = 'upload/personal/' . date('YmdHis');

        $crop = new CropAvatar($_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file'], 600, 600, $path);

          $response = array(
            'state'  => 200,
            'message' => $crop -> getMsg(),
            'result' => $crop -> getResult()
          );

          echo json_encode($response);
    }
}






