<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;

use App\PermanentQrcode;
use RestRose\Wechat\Publics\Qrcode;

class PermanentQrcodeController extends Controller
{
    /**
     * create menu
     *
     */
    public function add ()
    {
        $label = 'web';

        $qrcode = new Qrcode;
        $qrcode->expire_seconds = 0;
        $qrcode->scene_str = $label;
        $resault = $qrcode->createQrcode();

        print_r($resault);

        // PermanentQrcode::where('label')->first()
    }
}