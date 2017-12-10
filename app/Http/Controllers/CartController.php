<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Input;

use App\Cart;
use App\Goods;
use App\Order;
use App\PN;

use RestRose\Pipe\Tap;
use RestRose\Water\Shop;

class CartController extends Controller
{

    private $tap;
    private $shop;
    private $nav;

    function __construct()
    {
        $this->tap = new Tap;
        $this->shop = new Shop;
        //$this->helper = new Helper;
        $this->nav = [['href'=>'/panel', 'link'=>'面版'], ['href'=>'/cart', 'link'=>'订货车']];
        if($this->tap->isOrgMaster('supplier')) {
            array_push($this->nav, ['href'=>'/buy', 'link'=>'采购车']);
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
        // above : auth

        $conf = ['records' => $this->getCart('cart'), 'nav' => $this->nav];
        return view('shop.cart')->with('conf', $conf);
    }

    /**
     * buy cart
     *
     */
    public function indexBuy()
    {
        if(!$this->tap->isOrgMaster('supplier')) return $this->tap->fail();
        // above : auth

        $conf = ['records' => $this->getCart('buy'), 'nav' => $this->nav];
        return view('shop.buy')->with('conf', $conf);
    }

    /**
     * get cart or buy list
     *
     */
    public function getCart($key) 
    {
        if($key != 'cart' && $key != 'buy') die('CartController/getCart: bad key');

         $records = Cart::where('cart.'.$key, true)
                       ->where('cart.created_by', $this->tap->getSessionId())
                       ->leftJoin('staff', 'cart.created_by', '=', 'staff.id')
                       ->leftJoin('goods', 'cart.goods_id', '=', 'goods.id')
                       ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
                       ->select('cart.*', 'staff.name as staff_name', 'staff.id as staff_id', 'goods.name as goods_name', 'goods.id as goods_id', 'goods.img as goods_img', 'goods.price as goods_price', 'fashions.name as fashion_name')
                       ->get();
        return $records;
    }

    /**
     * ajax: build order from cart
     *
     */
    public function build(Request $request)
    {
        if(!$this->tap->isOrgMaster('shop') && !$this->tap->inOrg('shop') && !$this->tap->isOrgMaster('supplier')) return $this->tap->fail();
        
        //if(!$request->has('shop') || !$this->tap->departmentIs($request->shop, 'shop')) die("Cart/build: bad shop id");
        $key = $request->has('shop') ? 'cart' : 'buy';
        $url = $request->has('shop') ? "/order/show/" : "/order/buy/";

        $cart = $this->getCart($key);

        //if(!count($cart)) die("Cart/build: empty cart");

        $input = $request->all();
        $input = array_add($input, 'pn', $this->shop->getOrderPN());
        $input = array_add($input, 'created_by', $this->tap->getSessionId());
        $input = array_add($input, $key, true);

        // create PN
        $pn_id = PN::create($input)->id;

        $id_for_delete = [];
        foreach ($cart as $row) {
            $one = [];
            $one['pn_id'] = $pn_id;
            $one['goods_id'] = $row->goods_id;
            $one['num'] = $row->num;
            array_push($id_for_delete, $row->id);
            // create
            Order::create($one);
        }
        
        Cart::whereIn('id', $id_for_delete)->delete();

        $conf = [
                'class'=>'success', 
                'title'=>'成功', 
                'content'=>'您已经成功创建订单, 购物车已经清空', 
                'button'=>[
                    ['name'=>'查看', 'href'=>$url.$input['pn']],
                ],
            ];

        return view('note')->with('conf',$conf);
    }

    /**
     * build order : buy
     *
     */

    /**
     * ajax add
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        if(!$this->tap->isOrgMaster('shop') && !$this->tap->inOrg('shop') && !$this->tap->isOrgMaster('supplier')) return son_encode([], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        // above auth

        $json = [];

        if(!Input::has('buy') && !Input::has('cart')) return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        $created_by = $this->tap->getSessionId();
        $cart_or_buy = Input::has('cart') ? 'cart' : 'buy';
        $goods_id = Input::has('cart') ? Input::get('cart') : Input::get('buy');

        $has = Cart::where('goods_id', $goods_id)
                   ->where($cart_or_buy, true)
                   ->where('created_by', $created_by)
                   ->first();
        if(count($has)) {
            $num = $has->num + 1;
            $has->update(['num'=>$num]);
        }else{
            $input = [];
            $input = array_add($input, 'goods_id', $goods_id);
            $input = array_add($input, $cart_or_buy, true);
            $input = array_add($input, 'created_by', $created_by);

            Cart::create($input);
        }
        
        return $this->getNum();

    }

    /**
     * get Num ajax
     *
     */
    public function getNum() {
        $json = [];
        $json = array_add($json, 'num', $this->shop->getNum());
        $json = array_add($json, 'num_cart', $this->shop->getNumCart());
        $json = array_add($json, 'num_buy', $this->shop->getNumBuy());

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
