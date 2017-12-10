<?php

/*
|--------------------------------------------------------------------------
| Daffodil Project : Le Wan Jia
|--------------------------------------------------------------------------
|
| restrose.net
| june 2017
|
*/

// Exchang Data
Route::get('/way/clear', 'WayController@clearCache');
Route::get('/way/wechat_enterprise_ca', 'WayController@wechatCallbackEnterprise');
Route::get('/way/wechat_public_ca', 'WayController@wechatCallbackPublic');
Route::post('/way/git', 'WayController@git');

// wechat
Route::post('/way/wechat_public_ca', 'Wechat\RobotController@serve');

Route::get('/wechat/create_menu', 'Wechat\MenuController@createMenu'); 
Route::get('/wechat/permanent_qrcode', 'Wechat\PermanentQrcodeController@add'); 

// Route::get('/wechat/subscribe', 'Wechat\QrcodeController@showQrcode'); #subscribe
// Panel
Route::get('/panel', function() {
    return view('panel');
});
Route::get('/me', function() {
    return view('me');
});



// Free
Route::get('/free', function() {
    return view('free');
});

// Shop
Route::get('/', 'ItemController@index');
Route::get('/item', 'ItemController@index');
Route::get('/item/show/{id?}', 'ItemController@show');

// Normal
Route::get('/login', 'StaffController@login');
Route::post('/login/check', ['as'=>'login.check', 'uses'=>'StaffController@check']);
Route::get('/logout', 'StaffController@logout');


Route::get('/service', 'ServiceController@index');

// Need Wechat Openid
Route::group(['middleware' => ['wechat_openid']], function () {
    Route::get('/wechat/subscribe', 'Wechat\QrcodeController@showQrcode'); #subscribe
    Route::get('/card', 'CardController@index');
});

// Staff Only
Route::group(['middleware' => ['staff_only', 'state_ok']], function () {
    // ---- lwjzc.com only ---

    // items
    Route::get('/item/create', 'ItemController@create');
    Route::post('/item/store', ['as'=>'item.store', 'uses'=>'ItemController@store']);
    Route::post('/item/image/{id?}', 'ItemController@setImage');

    // card


    // card face
    Route::get('/card_face', 'CardFaceController@index');
    Route::get('/card_face/generate/{id?}', 'CardFaceController@generate');
    Route::post('/card_face/store', ['as'=>'card_face.store', 'uses'=>'CardFaceController@store']);

    // ---- lwjzc.com only : end ----

    // Staff
    Route::get('/staff/auto_login', 'StaffController@autoLogin');
    Route::get('/staffing', 'StaffController@index');
    Route::get('/staff/create', 'StaffController@create');
    Route::post('/staff/store', ['as'=>'staff.store', 'uses'=>'StaffController@store']);
    Route::get('/staff/show/{id?}', 'StaffController@show');
    Route::get('/staff/edit/{id?}', 'StaffController@edit');
    Route::post('/staff/update/{id?}', 'StaffController@update');
    Route::get('/staff/lock/{id?}', 'StaffController@lock');
    Route::get('/staff/unlock/{id?}', 'StaffController@unlock');
    Route::get('/staff/delete/{id?}', 'StaffController@delete');
    Route::post('/staff/image/{id?}', 'StaffController@setImage');

    // Shop
    Route::get('/shop', 'ShopController@index');
    Route::get('/shop/create', 'ShopController@create');
    Route::post('/shop/store', ['as'=>'shop.store', 'uses'=>'ShopController@store']);
    Route::get('/shop/show/{id?}', 'ShopController@show');

    // Fashion
    Route::get('/fashion', 'FashionController@index');
    Route::get('/fashion/create', 'FashionController@create');
    Route::get('/fashion/edit/{id?}', 'FashionController@edit');
    Route::get('/fashion/lock/{id?}', 'FashionController@lock');
    Route::get('/fashion/unlock/{id?}', 'FashionController@unlock');
    Route::post('/fashion/update/{id?}', ['as' => 'fashion.update', 'uses' => 'FashionController@update']);
    Route::post('/fashion/store', ['as'=>'fashion.store', 'uses'=>'FashionController@store']);
    Route::get('/fashion/show/{id?}', 'FashionController@show');
    Route::post('/fashion/image/{id?}', 'FashionController@setImage');
    Route::get('/fashion/delete/{id?}', 'FashionController@delete');

    // Goods
    Route::get('/goods', 'GoodsController@index');
    Route::get('/goods/create', 'GoodsController@create');
    Route::post('/goods/store', ['as'=>'goods.store', 'uses'=>'GoodsController@store']);
    Route::get('/goods/show/{id?}', 'GoodsController@show');
    Route::post('/goods/image/{id?}', 'GoodsController@setImage');
    Route::get('/goods/lock/{id?}', 'GoodsController@lock');
    Route::get('/goods/unlock/{id?}', 'GoodsController@unlock');
    Route::get('/goods/edit/{id?}', 'GoodsController@edit');
    Route::post('/goods/update/{id?}', ['as' => 'fashion.update', 'uses' => 'GoodsController@update']);
    Route::get('/goods/delete/{id?}', 'GoodsController@delete');
    Route::get('/goods/ajax/{id?}', 'GoodsController@showAjax');

    // Cart
    Route::get('/cart', 'CartController@index');
    Route::get('/cart/add', 'CartController@add');
    Route::get('/cart/num', 'CartController@getNum');
    Route::post('/cart/build', 'CartController@build');
    // Cart - buy
    Route::get('/buy', 'CartController@indexBuy');
    Route::post('/buy/build', 'CartController@build');

    // Order
    Route::get('/order', 'OrderController@index');
    Route::get('/order/show/{pn?}', 'OrderController@show');

    Route::get('/order/buy', 'OrderController@indexBuy');
    Route::get('/order/buy/{pn?}', 'OrderController@showBuy');

    // Offer
    Route::post('/offer/create', 'OfferController@create');
    Route::post('/offer/deliver', 'OfferController@deliver');
    Route::post('/offer/deliver/set', 'OfferController@deliverSet');
    Route::post('/offer/sale', 'OfferController@sale');
    Route::post('/offer/sale/set', 'OfferController@saleSet');
    Route::post('/offer/label/add', 'OfferController@labelAdd');
    Route::get('/offer', 'OfferController@index');
    Route::get('/offer/image_for_wechat/{id?}', 'OfferController@unionImages');

    // Excel
    Route::get('/excel/offer/label', 'ExcelController@labelPrint'); #label print


});

Route::get('/vip', function() {
    return view('vip');
});

/*
|--------------------------------------------------------------------------
| Test Rutes
|--------------------------------------------------------------------------
|
*/

// without SCRF
Route::post('/way/test', 'WayController@test');


Route::get('/test', function () {
    $conf = [
                'class'=>'info', 
                'title'=>'权限不足', 
                'content'=>'what is a fuk', 
                'button'=>[
                    ['type'=>'close'],
                    ['type'=>'back'],
                    ['type'=>'panel'],
                    ['type'=>'home'],
                    ['name'=>'自定义', 'href'=>'/login'],
                    ['name'=>'自定义1', 'js'=>'fuck'],
                    ['name'=>'自定义2', 'js'=>'fuck1', 'icon'=>'university'],
                ],
            ];

    return view('note')->with('conf',$conf);

});

Route::get('/test1', 'CardController@daily');

Route::get('/test2', function () {
   Session::flush();
   Cookie::queue('openid', 0, -1);
});

Route::get('/test3', function () {
$xml = '<xml><ToUserName><![CDATA[gh_68e05bf354c8]]></ToUserName>
<FromUserName><![CDATA[ozgC81CU0k-nvsLejTCFq0pzJrGU]]></FromUserName>
<CreateTime>1502436844</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
<EventKey><![CDATA[]]></EventKey>
</xml>';
    $a = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);  
    //$a = Cache::get('tmp');
    //echo $a;
    print_r($a);
});

Route::get('/test4', function () {
    $a = new App\Http\Controllers\Wechat\Robot\Subscribe;
    $a->sendGift('qrscene_1');
});









