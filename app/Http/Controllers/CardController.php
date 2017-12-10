<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Input;

use App\Card;
use App\CardFace;
use App\Item;
use App\Wechat;

use RestRose\Pipe\Tap;
use RestRose\Water\Helper;

class CardController extends Controller
{
    public $limit_daily=2;
    public $expires_seconds=604800;

    public $owner_wechat_id, $card_face_id;

    public $tap, $helper;

    function __construct()
    {
        if(Session::has('id')) $this->tap = new Tap;
        $this->helper = new Helper;

        $this->nav = [['href'=>'/panel', 'link'=>'面版'], ['href'=>'/card', 'link'=>'卡券']];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $openid = Session::get('openid');
        $my_id = Wechat::where('openid', $openid)->first()->id;

        $records = Card::where('card.owner_wechat_id', $my_id)
                        ->leftJoin('card_face', 'card.card_face_id', '=', 'card_face.id')
                        ->leftJoin('items', 'card_face.item_id', '=', 'items.id')
                        ->select('card.*', 'card_face.ratio as card_face_ratio', 'card_face.title as card_face_title', 'items.title as item_title', 'items.img as item_img')
                        ->orderBy('card.created_at', 'desc')
                        ->paginate(40);

        $conf = ['records' => $records, 'nav' => $this->nav];
        if(Input::has('key')) $conf = array_add($conf, 'key', Input::get('key'));

        return view('card.list')->with('conf', $conf);
    }


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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generate($id)
    {
        $target = Item::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
