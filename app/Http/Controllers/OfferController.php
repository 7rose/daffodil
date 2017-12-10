<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Session;
use DB;
use Input;
use URL;

use App\Cart;
use App\Offer;
use App\Order;
use App\PN;

use Carbon\Carbon;
use Intervention\Image\ImageManager;

use RestRose\Water\Shop;
use RestRose\Pipe\Tap;
use RestRose\Wechat\Enterprise\Api;

class OfferController extends Controller
{
    private $tap;
    private $shop;
    private $nav;

    function __construct()
    {
        $this->tap = new Tap;
        $this->shop = new Shop;
        //$this->helper = new Helper;
        $this->nav = [['href'=>'/panel', 'link'=>'面版'], ['href'=>'/fashion', 'link'=>'款式'], ['href'=>'/goods', 'link'=>'样品'],['href'=>'/offer', 'link'=>'存货']];
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->tap->isOrgMaster('shop') && !$this->tap->inOrg('shop')) return $this->tap->fail();
        // above auth

        $null = Offer::all();
        if(!count($null)) {
            $conf=['records' => $null, 'nav' => $this->nav];
            return view('shop.offer')->with('conf', $conf);
        }

        $records = $this->baseIndex()
                ->where(function($query){
                    if($this->tap->isOrgMaster('shop')){
                        if(Input::has('shop') && Input::get('shop') != ''){
                            $query->where('shop',Input::get('shop'));
                        }else{
                            $query->whereNull('offers.shop');
                        }
                        
                    }elseif($this->tap->inOrg('shop')){
                        $query->where('offers.shop',$this->tap->getHeadDepartment());
                    }
                })
                ->where(function($query){
                   if(Input::has('key') && Input::get('key') != ''){
                        $query->Where('offers.sn', 'LIKE', '%'.Input::get('key').'%')
                              ->orWhere('offers.other', 'LIKE', '%'.Input::get('key').'%')
                              ->orWhere('offers.content', 'LIKE', '%'.Input::get('key').'%')
                              ->orWhere('fashions.name', 'LIKE', '%'.Input::get('key').'%')
                              ->orWhere('goods.name', 'LIKE', '%'.Input::get('key').'%');
                    } 
                })
                ->select(
                    'offers.id', 'offers.sn', 'offers.goods_sn', 'offers.sold',
                    'goods.price as goods_price','goods.name as goods_name',
                    'fashions.name as fashion_name'
                    )
                ->paginate(50);

        // shops list
        $shops = $this->baseIndex() 
                 ->select('offers.shop', 
                 'departments.name as shop_name', 
                 DB::raw('count(distinct(offers.goods_sn)) as goods_sn'),
                 DB::raw('count(offers.id) as all_num'), 
                 DB::raw('sum(goods.price) as all_price')
                 )
        ->groupBy('offers.shop')
        ->leftJoin('offers as shops', 'offers.id', '=', 'shops.id')
        ->get();

        $info_arr  = [];

        if($this->tap->inOrg('shop')){
            $info = $this->baseIndex() 
                        ->where('offers.shop',$this->tap->getHeadDepartment())
                        ->select('offers.shop', 'offers.sold', 
                                 'departments.name as shop_name', 
                                 DB::raw('count(offers.id) as all_num'), 
                                 DB::raw('sum(goods.price) as all_price')
                                 )
                        ->groupBy('offers.sold')
                        ->orderBy('offers.sold')
                        ->get();
                        //->toJson();

            foreach ($info as $row) {
                $tmp = [];
                $tmp = array_add($tmp, 'shop_name',$row->shop_name);
                $tmp = array_add($tmp, 'all_num', $row->all_num);
                $tmp = array_add($tmp, 'all_price',$row->all_price);
                array_push($info_arr, $tmp);
            } 
        }

        $conf = ['records' => $records, 'shops' => $shops, 'nav' => $this->nav];
        if(Input::has('key') && Input::get('key') != '') {
            $conf = array_add($conf, 'key', Input::get('key'));
        }
        if(Input::has('shop') && Input::get('shop') != '') {
            $conf = array_add($conf, 'shop', Input::get('shop'));
        }

        if(count($info_arr)) {
            $conf = array_add($conf, 'shop_info', $info_arr);
            $conf = array_add($conf, 'shop', $this->tap->getHeadDepartment());
        }

        // label
        $labels = Offer::where('need_label',true)
                       ->where('sold', false)
                       ->select(DB::raw('count(id) as num'))
                       ->first();

        $conf = array_add($conf, 'label_num', $labels->num);


        return view('shop.offer')->with('conf', $conf);
    }

    // shop
    private function shopIndex($shop_id)
    {
        $shop = $this->baseIndex() 
                 ->where('offers.shop', $shop_id)
                 ->select('offers.*', 
                 'departments.name as shop_name', 
                 DB::raw('count(distinct(offers.goods_sn)) as goods_sn'),
                 DB::raw('count(offers.id) as all_num'), 
                 DB::raw('sum(goods.price) as all_price')
                 )
        ->groupBy('offers.sold')
        ->get();
    }


    // base index
    private function baseIndex()
    {
        $base = DB::table('offers')
                    ->where(function ($query){
                            if(!$this->tap->isAdmin()) {
                                $query->where('offers.hide', false)
                                      ->where('offers.locked', false);                               
                            }
                        })
                    // ->leftJoin('orders', 'offers.order_pn', '=', 'orders.pn')
                    // ->leftJoin('orders as buy', 'offers.order_buy_pn', '=', 'orders.pn')
                    ->leftJoin('goods', 'offers.goods_sn', '=', 'goods.sn')
                    ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
                    ->leftJoin('departments', 'offers.shop', '=', 'departments.id')
                    ->leftJoin('staff', 'offers.created_by', '=', 'staff.id');
        return $base;
    }

    /**
     * sale
     *
     * @return \Illuminate\Http\Response
     */
    public function sale(Request $request)
    {
        if(!$this->tap->isOrgMaster('shop') && !$this->tap->inOrg('shop')) return $this->tap->fail();
        // above auth

        $ajax_post = $request->json()->all();

        $target = Offer::where('offers.hide', false)
                       ->where('offers.locked', false)
                       ->leftJoin('goods', 'offers.goods_sn', '=', 'goods.sn')
                       ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
                       ->select(
                                'offers.*', 
                                'goods.name as goods_name', 'goods.price as goods_price', 'goods.img as goods_img', 'goods.stone as goods_stone', 'goods.gold as goods_gold',
                                'fashions.name as fashion_name'
                                )
                       ->find($ajax_post['id'])
                       ->toJson();

        return $target;
    }

    /**
     * sale set
     *
     * @return \Illuminate\Http\Response
     */
    public function saleSet(Request $request)
    {
        if(!$this->tap->isOrgMaster('shop') && !$this->tap->inOrg('shop')) return $this->tap->fail();
        // above auth

        $ajax_post = $request->json()->all();

        $carbon = new Carbon;

        $updates = ['sold' => true, 'sold_price' => $ajax_post['sold_price']];
        $updates = array_add($updates, 'sold_by', $this->tap->getSessionId());
        $updates = array_add($updates, 'sold_time', $carbon->now());

        $exsists = Offer::find($ajax_post['id']);

        if($exsists->sold) {
            $json = ['resault' => 'fail', 'msg' => '该货品已经销售'];
        }else{
            $exsists->update($updates);
            $json = ['resault' => 'ok', 'msg' => '您已经成功登记销售!'];

            // wechat notice
            $full_info = Offer::leftJoin('goods', 'offers.goods_sn', '=', 'goods.sn')
                              ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
                              ->leftJoin('departments', 'offers.shop', '=', 'departments.id')
                              ->leftJoin('staff', 'offers.sold_by', '=', 'staff.id')
                              ->leftJoin('departments as s', 'staff.department', '=', 's.id')
                              ->select
                                (
                                    'offers.sold_price', 'offers.goods_sn', 'offers.sn', 'offers.sold_by', 'offers.id', 
                                    'goods.name as goods_name', 'goods.price as goods_price', 
                                    'fashions.name as fashion_name', 
                                    'departments.name as shop_name', 
                                    'staff.name as staff_name', 'staff.sn as staff_sn', 
                                    's.name as staff_department_name'
                                )
                              ->where('offers.id', $ajax_post['id'])
                              ->first();

            // prepare for wechat
            $title = '销售: ￥'.$full_info->sold_price;
            $description = $full_info->shop_name.': '.$full_info->fashion_name.'('.$full_info->goods_name.'), 原价: ￥'.$full_info->goods_price.', 编号:'.$full_info->sn.', 货号: '.$full_info->goods_sn.'; 销售人: '.$full_info->staff_name.', 工号-'.$full_info->staff_sn;
            $url = '/shop/show/'.$full_info->sold_by;
            $url = URL::asset($url);
            $picurl = '/offer/image_for_wechat/'.$full_info->id;
            $picurl = URL::asset($picurl);
            $user_id_list = '1|5';

            $wechat = new Api;
            $articles = ['title'=>$title,'description'=>$description,'url'=>$url,'picurl'=>$picurl];
            //print_r($articles);
            $wechat->sendNews($user_id_list,$articles);
        }
        return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);    
    }

    /**
     * union images
     *
     * wechat : 640 * 320;
     */
    public function unionImages($id)
    {
        $target = Offer::leftJoin('goods', 'offers.goods_sn', '=', 'goods.sn')
                       ->leftJoin('staff', 'offers.sold_by', '=', 'staff.id')
                       ->select('goods.img as goods_img', 'staff.img as staff_img')
                       ->find($id);

        $goods_img = $target->goods_img ? $target->goods_img : 'daffodil/img/default.png';
        $staff_img = $target->staff_img ? $target->staff_img : 'daffodil/img/default.png';


        $manager = new ImageManager;
        $goods_img = $manager->make($goods_img)->resize(320, 320);
        $staff_img = $manager->make($staff_img)->resize(320, 320);
        $goods_img->resizeCanvas(640, 320, 'bottom-left');
        $goods_img->insert($staff_img, 'bottom-right');
        //echo $goods_img->response('png', 70);
        return $goods_img->response();
    }


    /**
     * create
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!$this->tap->isOrgMaster('supplier')) {
            $json = ['resault' => 'fail', 'msg' => '权限不足'];
            return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
        // above auth 

        $ajax_post = $request->json()->all();
        $order_buy_pn = $ajax_post[0]['order_buy_pn'];
        $goods_sn = $ajax_post[0]['goods_sn'];

        if($this->existsIn($order_buy_pn, $goods_sn)) {
            $json = ['resault' => 'fail', 'msg' => '已经存在相同记录'];
            return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

        for ($i=0; $i < count($ajax_post); $i++) { 
            $one = $ajax_post[$i];
            $one = array_add($one, 'sn', $this->shop->getOfferSN());
            $one = array_add($one, 'created_by', $this->tap->getSessionId());
            Offer::create($one);
        }
        $json = ['resault' => 'ok', 'msg' => '操作成功'];
        return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

    // check exsists in offer
    public function existsIn($pn,$goods_sn)
    {
        $target = Offer::where('order_buy_pn', $pn)->where('goods_sn', $goods_sn)->first();
        return count($target) ? true : false;
    }

    /**
     * deliver
     *
     * @return \Illuminate\Http\Response
     */
    //public function deliver()
    public function deliver(Request $request)
    {
        if(!$this->tap->isOrgMaster('shop')) {
            $json = ['resault' => 'fail', 'msg' => '权限不足'];
            return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
        // above auth 

        $ajax_post = $request->json()->all();

        $records = Offer::where('offers.goods_sn', $ajax_post['goods_sn'])
                        ->where('offers.hide',false)
                        ->where('offers.locked',false)
                        ->where('offers.sold',false)
                        ->leftJoin('departments', 'offers.shop', '=', 'departments.id')
                        ->leftJoin('goods', 'offers.goods_sn', '=', 'goods.sn')
                        ->select('offers.sn', 'departments.name as shop_name', 'goods.img as goods_img')
                        ->orderBy('shop')
                        ->orderBy('sn')
                        ->get()
                        ->toJson();
        
        return  $records;
    }
    /**
     * deliver set
     *
     * @return \Illuminate\Http\Response
     */
    //public function deliver()
    public function deliverSet(Request $request)
    {
        if(!$this->tap->isOrgMaster('shop')) {
            $json = ['resault' => 'fail', 'msg' => '权限不足'];
            return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
        // above auth  

        $ajax_post = $request->json()->all();
        $sn_array = explode(',', $ajax_post['str']);
        $goods_sn = $ajax_post['goods_sn'];
        $order_pn = $ajax_post['pn'];
        $num = $ajax_post['num'];
        $pn = PN::where('pn', $order_pn)->first();
        if($ajax_post == '' || !count($pn)) {
            $json = ['resault' => 'fail', 'msg' => '数据错误'];
            return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
        $shop = $pn->shop;
        $updates = ['order_pn' => $order_pn, 'shop' => $shop];

        $exists = Offer::where('goods_sn', $goods_sn)
                       ->where('shop', $shop)
                       ->where('order_pn', $order_pn)
                       ->get();
        if(count($exists) >= $num){
            $json = ['resault' => 'fail', 'msg' => '数据已经存在'];
            return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        } 

        $do = Offer::whereIn('sn',$sn_array)->update($updates);

        $json = ['resault' => 'ok', 'msg' => '操作成功'];
        return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

    /**
     * add label print list
     *
     */
    public function labelAdd(Request $request)
    {
        $ajax_post = $request->json()->all();
        $need_label = Offer::find($ajax_post['id'])->update(['need_label' => true]);
        $json = ['resault' => 'ok', 'msg' => '成功'];
        return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
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
