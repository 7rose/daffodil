<?php
namespace App\Http\Controllers\Wechat\Robot;

use App\Wechat;
use App\CardFace;
use App\Card;
/**
* Gift 
*
*/
class Gift
{
	public $limit_daily=1;
    public $expires_seconds=604800;

    public $owner_wechat_id, $card_face_id;

   // random daily
    public function daily ()
    {

        $luck_persons = Wechat::orderByRaw('RAND()')
                              ->take($this->limit_daily)
                              ->select('id')
                              ->get()
                              ->toArray();

        $gift = CardFace::orderByRaw('RAND()')
                        ->take($this->limit_daily)
                        ->select('id')
                        ->get()
                        ->toArray();
        // $all_insert = [];
        if(count($luck_persons) < $this->limit_daily || count($gift) < 1) exit;

        for ($i=0; $i < count($luck_persons); $i++) { 
            $insert = [
                        'owner_wechat_id' => $luck_persons[$i]['id'], 
                        'card_face_id' => $gift[$i]['id'],
                        'from' => '每日抽奖',
                        'expires_in' => time() + $this->expires_seconds
                      ];
            // array_push($all_insert, $insert);
            Card::create($insert);
        }

        // Card::create($all_insert);
    }

    // gift for advice
    public function gift () 
    {
        $gift = [
                'owner_wechat_id' => $this->owner_wechat_id, 
                'card_face_id' => $this->card_face_id,
                'from' => '推荐赠送',
                'expires_in' => time() + $this->expires_seconds
              ];
        Card::create($gift);
    }
}