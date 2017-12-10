<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use Session;

use App\PermanentQrcode;
use App\Wechat;
use RestRose\Wechat\Publics\Qrcode;

class QrcodeController extends Controller
{
    public $default_label = 'web';
    /**
     * create menu
     *
     */
    public function showQrcode ()
    {
        if(Session::has('openid')) {
            return view('subscribe')->with('url', $this->getUrl(Session::get('openid')));
        } else {
            return view('subscribe')->with('url', $this->usePermanentQrcode($this->default_label));
        }
    }

    // get permanent qrcode
    public function usePermanentQrcode ($label) 
    {
        $target = PermanentQrcode::where('label', $label)->first();
        return $target ? $target->url : $this->setPermanentQrcode($label);
    }

    // set permanent qrcode
    private function setPermanentQrcode ($label) 
    {
        $qrcode = new Qrcode;
        $qrcode->expire_seconds = 0;
        $qrcode->scene_str = $label;
        $resault = $qrcode->createQrcode();
        $resault = array_add($resault, 'label', $label);
        if(Session::has('id')) $resault = array_add($resault, 'staff_id', Session::get('id'));
        PermanentQrcode::create($resault);
        return $resault['url'];
    }

    // get Url
    public function getUrl ($openid) 
    {
        $target = Wechat::where('openid', $openid)->first();
        if (!$target) return $this->usePermanentQrcode($this->default_label);

        return $target->qrcode_url && (intval($target->qrcode_seconds) > time()) ? $target->qrcode_url : $this->setQrcode($openid);
    }

    // set qrcode to wechat table
    private function setQrcode ($openid) 
    {
        $target = Wechat::where('openid', $openid)->firstOrFail();

        $qrcode = new Qrcode;
        $qrcode->scene_id = $target->id;
        $resault = $qrcode->createQrcode();

        $updates = ['qrcode_ticket'=>$resault['ticket'] , 'qrcode_url'=>$resault['url'] , 'qrcode_seconds'=> (time()+604800-3600) ];
        $target->update($updates);
        return $resault['url'];
    }

    // end
}










