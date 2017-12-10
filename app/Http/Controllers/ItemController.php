<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Session;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Http\Forms\Item\ItemForm;

use App\Item;
use RestRose\Pipe\Tap;
use RestRose\Water\Helper;
use RestRose\Water\CropAvatar;

class ItemController extends Controller
{
    use FormBuilderTrait;

    public $tap, $helper;

    function __construct()
    {
        if(Session::has('id')) $this->tap = new Tap;
        $this->helper = new Helper;

        $this->nav = [['href'=>'/panel', 'link'=>'面版'], ['href'=>'/item', 'link'=>'时鲜美味']];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(!$this->tap->isOrgMaster('shop') && !$this->tap->inOrg('shop')) return $this->tap->fail();
        // above : auth

        $records = Item::where(function ($query) { 
                                // if(!$this->tap->isAdmin()) {
                                //     $query->where('items.show', true);
                                //     // $query->where('items.locked', false);
                                // }
                                if(Input::has('key')) {
                                    $query->Where('items.title', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('items.summary', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('items.content', 'LIKE', '%'.Input::get('key').'%');
                                }
                            })
                          ->leftJoin('config', 'items.type', '=', 'config.id')
                          ->leftJoin('config as g', 'items.unit', '=', 'g.id')
                          ->select('items.*', 'config.text as type_name', 'g.text as unit_name')
                          ->paginate(40);

        $conf = ['records' => $records, 'nav' => $this->nav];
        if(Input::has('key')) $conf = array_add($conf, 'key', Input::get('key'));

        return view('item.list')->with('conf', $conf);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Session::has('id')) abort(403);

        $form = $this->form(ItemForm::class, [
            'method' => 'POST',
            'url' => route('item.store')
        ]);
        return view('form', compact('form'))->with('conf',['title'=>'发布美味', 'icon'=>'leaf', 'nav'=>$this->nav]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Session::has('id')) abort(403);

        $form = $this->form(ItemForm::class);
        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        $input = $request->all();
       
        $input['created_by'] = $this->tap->getSessionId();

        // print_r($input);

        $new_id =  Item::create($input)->id;

        $conf = [
                'class'=>'success', 
                'title'=>'添加成功', 
                'content'=>'您添加的商品已成功, 但如果不完成添加图片, 商品将不会公布', 
                'button'=>[
                    ['name'=>'设置图片', 'href'=>'/item/show/'.$new_id],
                    ['name'=>'不设置继续添加', 'href'=>'/item/create'],
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
        // if(!$this->tap->isOrgMaster('shop') && !$this->tap->inOrg('shop')) return $this->tap->fail();
        // above : auth

        $record = Item::leftJoin('config', 'items.type', '=', 'config.id')
                      ->leftJoin('config as g', 'items.unit', '=', 'g.id')
                      ->select('items.*', 'config.text as type_name', 'g.text as unit_name')
                      ->findOrfail($id);

        return view('item.show')->with('conf', ['record' => $record, 'nav' => $this->nav]);
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

    // set image
    public function setImage(Request $request, $id) 
    {
        // if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $me = Item::find($id);
        $path = base_path().'/public/'.$me->img;

        if($me->img != '' && $me->img != null && file_exists($path)) unlink($path);

        $path = 'upload/item/'.$id.'-'.date('YmdHis');

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
