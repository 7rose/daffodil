<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Session;
use DB;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Http\Forms\Shop\GoodsForm;

use App\Goods;
use App\Fashion;
use App\Offer;
use RestRose\Water\Helper;
use RestRose\Water\CropAvatar;
use RestRose\Water\Shop;
use RestRose\Pipe\Tap;

class GoodsController extends Controller
{
    use FormBuilderTrait;

    private $nav;
    private $tap;
    private $helper;
    private $shop;

    private $login_id;

    function __construct()
    {
        $this->tap = new Tap;
        $this->helper = new Helper;
        $this->shop = new Shop;

        $this->nav = [['href'=>'/panel', 'link'=>'面版'], ['href'=>'/fashion', 'link'=>'款式'], ['href'=>'/goods', 'link'=>'样品'],['href'=>'/offer', 'link'=>'存货']];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $null = Goods::all();
        if(!count($null)) {
            $conf=['records' => $null, 'nav' => $this->nav];
            return view('shop.goods')->with('conf', $conf);
        }

        $records = DB::table('goods')
                     ->where(function ($query) { 
                                if(!$this->tap->isAdmin()) {
                                    $query->where('goods.hide', false);
                                    // $query->where('goods.locked', false);
                                }
                                if(Input::has('key')) {
                                    $query->Where('goods.name', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('goods.name_en', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('goods.sn', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('goods.content', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('goods.content_en', 'LIKE', '%'.Input::get('key').'%');
                                }
                                if(Input::has('fashion')) {
                                    $query->where('goods.fashion', Input::get('fashion'));
                                }
                            })
                          ->leftJoin('config', 'goods.type', '=', 'config.id')
                          ->leftJoin('config as g', 'goods.gender', '=', 'g.id')
                          ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
                          ->leftJoin('offers', function($leftJoin)
                               {
                                //if($this->hasOffer()){
                                    $leftJoin->on('goods.sn', '=', 'offers.goods_sn')
                                            ->where('offers.hide', '=', false)
                                            ->where('offers.locked', '=', false)
                                            ->where('offers.sold', '=', false);
                                            //->where('offers.shop', '=', NULL);
                                //}

                               })

                          ->select('goods.*', 'config.text as type_name', 'g.text as gender_name', 'fashions.name as fashion_name', DB::raw('count(offers.goods_sn) as offer_count'))
                          ->groupBy('goods.sn')
                          ->paginate(40);
        $conf = ['records' => $records, 'nav' => $this->nav];
        if(Input::has('key')) $conf = array_add($conf, 'key', Input::get('key'));

        return view('shop.goods')->with('conf', $conf);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = '成品';

        if(Input::has('fashion')) {
            $fashion_name = Fashion::find(Input::get('fashion'));
            if(count($fashion_name)) $title .= ' :'.$fashion_name->name;
        }

        $form = $this->form(GoodsForm::class, [
            'method' => 'POST',
            'url' => route('goods.store')
        ]);

        return view('form', compact('form'))->with('conf',['title'=>$title, 'icon'=>'diamond', 'nav'=>$this->nav]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = $this->form(GoodsForm::class);
        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        $input = $request->all();
        //print_r($input);
        if($request->has('lock_shop')) {
            Session::put('lock_shop', $request->get('shop'));
        }else{
            Session::pull('lock_shop');
        }

        if(!$request->has('num')) $input['num'] = 1;
       
        $input['created_by'] = $this->tap->getSessionId();

        for ($i=0; $i < $input['num'] ; $i++) { 
            $input['sn'] = $this->shop->getGoodsSN();
            Goods::create($input);
        }

        $conf = [
                'class'=>'success', 
                'title'=>'添加成功', 
                'content'=>'您添加的货物已成功, 请查看以完善资料', 
                'button'=>[
                    ['name'=>'查看列表', 'href'=>'/goods'],
                ],
            ];

        return view('note')->with('conf',$conf);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->tap->isOrgMaster('shop') && !$this->tap->inOrg('shop')) return $this->tap->fail();
        // above : auth

        $record = Goods::leftJoin('config', 'goods.type', '=', 'config.id')
                         ->leftJoin('config as g', 'goods.gender', '=', 'g.id')
                         ->select('goods.*', 'config.text as type_name', 'g.text as gender_name')
                         ->find($id);
        if(!count($record)) die('FashionController/show: bad id');

        return view('shop.goods-show')->with('conf', ['record' => $record, 'nav' => $this->nav]);
    }

    /**
     *
     *
     */
    public function setImage(Request $request, $id) 
    {
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $me = Goods::find($id);
        $path = base_path().'/public/'.$me->img;

        if($me->img != '' && $me->img != null && file_exists($path)) unlink($path);

        $path = 'upload/goods/'.$id.'-'.date('YmdHis');

        //$crop = new CropAvatar($_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file'], 600, 600, $path);
        $crop = new CropAvatar($request->avatar_src, $request->avatar_data, $_FILES['avatar_file'], 400, 400, $path);
          $response = array(
            'state'  => 200,
            'message' => $crop -> getMsg(),
            'result' => $crop -> getResult()
          );

          $me->update(['img'=>$path.'.png']);

          echo json_encode($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $record = Goods::find($id);

        $form = $this->form(GoodsForm::class, [
            'method' => 'POST',
            'model' => $record,
            //'url' => route('fashion.update')
            'url' => '/goods/update/'.$id
        ]);

        return view('form', compact('form'))->with('conf',['title'=>'信息修改', 'icon'=>'diamond', 'nav'=>$this->nav]);

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
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $form = $this->form(GoodsForm::class);

        $form->validate(['name' => 'required|min:1|max:16']);

        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        $input = $request->all();

        Goods::find($id)->update($input);

        $conf = [
                'class'=>'success', 
                'title'=>'成功', 
                'content'=>'您已经成功修改信息!', 
                'button'=>[
                    ['name'=>'查看', 'icon'=>'search', 'href'=>'/goods/show/'.$id]
                ],
            ];
        return view('note')->with('conf',$conf);
    }

    /**
     * delete
     *
     */
    public function delete($id) 
    {
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $target = Goods::find($id);

        $img = base_path().'/public/'.$target->img;
        if($target->img != '' && $target->img != null && file_exists($img)) unlink($img);

        if($this->tap->isAdmin()) {
            $target->delete();
        }else{
            $target->update(['hide' => true]);
        }
        
        $conf = [
                'class'=>'success', 
                'title'=>'成功', 
                'content'=>'删除成功', 
                'button'=>[
                    ['name'=>'返回列表', 'href'=>'/goods']
                ],
            ];

        return view('note')->with('conf',$conf);
    }

    /**
     * lock
     *
     */
    public function lock($id) 
    {
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $target = Goods::find($id)->update(['locked' => true]);
        return redirect('/goods/show/'.$id);
    }

    /**
     * lock
     *
     */
    public function unlock($id) 
    {
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $target = Goods::find($id)->update(['locked' => false]);
        return redirect('/goods/show/'.$id);
    }


    /**
     * goods info from ajax
     *
     */
    public function showAjax($id)
    {
        $target = Goods::find($id);
        if(!count($target)) return [];
        $json = ['stone' => $target->stone, 'gold' => $target->gold, 'img' => $target->img, 'sn' => $target->sn];
        return json_encode($json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }

    /**
     * End
     *
     */
}
