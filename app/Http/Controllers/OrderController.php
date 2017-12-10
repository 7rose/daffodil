<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

use PN;
use App\Order;
use App\Offer;
use RestRose\Pipe\Tap;

class OrderController extends Controller
{
    private $tap;
    private $nav;

    function __construct()
    {
        $this->tap = new Tap;
        //$this->shop = new Shop;
        //$this->helper = new Helper;
        $this->nav = [['href'=>'/panel', 'link'=>'面版'], ['href'=>'/order', 'link'=>'订货单']];
        if($this->tap->isOrgMaster('supplier')) {
            array_push($this->nav, ['href'=>'/order/buy', 'link'=>'采购单']);
        }
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

        $conf = ['records' => $this->getOrderList('cart'), 'nav' => $this->nav];
        return view('shop.order')->with('conf', $conf);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBuy()
    {
        if(!$this->tap->isOrgMaster('supplier')) return $this->tap->fail();
        // above auth 

        $conf = ['records' => $this->getOrderList('buy'), 'nav' => $this->nav];
        return view('shop.order-buy')->with('conf', $conf);
    }

    /**
     * get list
     *
     */
    private function getOrderList($key) 
    {
        // $records = DB::table('orders')
        //              ->where('orders.'.$key, true)
        //              ->leftJoin('goods', 'orders.goods_id', '=', 'goods.id')
        //              ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
        //              ->leftJoin('departments', 'orders.shop', '=', 'departments.id')
        //              ->leftJoin('staff', 'orders.created_by', '=', 'staff.id')
        //              ->select('orders.*', DB::raw('group_concat(orders.num) as order_num, group_concat(goods.price) as goods_price, group_concat(fashions.name) as fashion_name, group_concat(goods.name) as goods_name, group_concat(goods.id) as goods_id'), DB::raw('sum(orders.num) as sum_num'), DB::raw('sum(orders.num * goods.price) as sum_price'), 'departments.name as shop_name', 'staff.name as staff_name')
        //              ->groupBy('orders.pn')
        //              ->orderBy('orders.created_at', 'DESC')
        //              ->paginate(40);

        $records = DB::table('pn')
                     ->leftJoin('orders', 'pn.id', '=', 'orders.pn_id')
                     ->leftJoin('goods', 'orders.goods_id', '=', 'goods.id')
                     ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
                     ->leftJoin('departments', 'pn.shop', '=', 'departments.id')
                     ->leftJoin('staff', 'pn.created_by', '=', 'staff.id')
                     ->leftJoin('offers', 'pn.pn', '=', 'offers.order_pn')
                     ->leftJoin('offers as b', 'pn.pn', '=', 'b.order_buy_pn')
                     ->where('pn.'.$key, true)
                     ->select
                        (
                            'pn.*',
                            'departments.name as shop_name',
                            DB::raw('group_concat(orders.num) as order_num, group_concat(goods.price) as goods_price, group_concat(fashions.name) as fashion_name, group_concat(goods.name) as goods_name, group_concat(goods.id) as goods_id'), 
                            DB::raw('sum(distinct(orders.num)) as sum_num'), 
                            DB::raw('sum(distinct(orders.num * goods.price)) as sum_price'),
                            DB::raw('count(distinct(offers.id)) as send_num'), 
                            DB::raw('count(distinct(b.id)) as buy_num')
                        )
                     ->groupBy('pn.pn')
                     ->distinct()
                     ->orderBy('pn.created_at', 'DESC')
                     ->paginate(40);

        return $records;
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
    public function show($pn)
    {
        if(!$this->tap->isOrgMaster('shop') && !$this->tap->inOrg('shop')) return $this->tap->fail();
        // above auth
        return view('shop.order-show')->with('conf', ['records' => $this->getOne($pn), 'nav' => $this->nav, 'pn' => $pn]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showBuy($pn)
    {
        if(!$this->tap->isOrgMaster('supplier')) return $this->tap->fail();
        // above auth 

        return view('shop.order-buy-show')->with('conf', ['records' => $this->getOne($pn), 'nav' => $this->nav, 'pn' => $pn]);
    }

    /**
     * get one
     *
     */
    private function getOne($pn) 
    {
        // $records = DB::table('orders')
        //                ->where('orders.pn', $pn)
        //                ->leftJoin('goods', 'orders.goods_id', '=', 'goods.id')
        //                ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
        //                ->leftJoin('departments', 'orders.shop', '=', 'departments.id')
        //                ->leftJoin('staff', 'orders.created_by', '=', 'staff.id')
        //                ->leftJoin('offers', function($leftJoin)
        //                     {
        //                         $leftJoin->on('orders.pn', '=', 'offers.order_buy_pn')
        //                                  ->on('goods.sn', '=', 'offers.goods_sn');            
        //                     })
        //                ->leftJoin('offers as a', function($leftJoin)
        //                     {
        //                         $leftJoin->on('orders.pn', '=', 'a.order_pn')
        //                                  ->on('orders.shop', '=', 'a.shop')            
        //                                  ->on('goods.sn', '=', 'a.goods_sn');            
        //                     })
        //                ->select('orders.*', 'goods.id as goods_id', 'goods.sn as goods_sn', 'goods.name as goods_name', 'goods.img as goods_img', 'goods.price as goods_price', 'fashions.name as fashion_name', 'departments.name as shop_name', 'staff.name as staff_name', 'offers.order_buy_pn as offer_count', 'a.order_pn as offer_count_pn')
        //                ->distinct()
        //                ->get();

        $records = DB::table('orders')
                     ->leftJoin('pn', 'orders.pn_id', '=', 'pn.id')
                     ->where('pn.pn', $pn)

                     ->leftJoin('goods', 'orders.goods_id', '=', 'goods.id')
                     ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
                     ->leftJoin('offers', function($join)
                        {
                            $join->on('pn.pn', '=', 'offers.order_pn')
                                 ->on('goods.sn', '=', 'offers.goods_sn');
                        })
                     ->leftJoin('offers as b', function($join)
                        {
                            $join->on('pn.pn', '=', 'b.order_buy_pn')
                                 ->on('goods.sn', '=', 'b.goods_sn');
                        })
                     ->select
                        (
                            'pn.pn', 
                            'orders.num', 
                            'fashions.name as fashion_name', 
                            'goods.name as goods_name', 'goods.sn as goods_sn', 'goods.id as goods_id',
                            DB::raw('count(offers.id) as send_num'), 
                            DB::raw('count(b.id) as buy_num')
                        )
                     ->groupBy('orders.goods_id')
                     ->get();

        return count($records) ? $records : die('OrderController/getOne: bad id');
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
