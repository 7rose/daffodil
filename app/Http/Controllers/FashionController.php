<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Http\Forms\Shop\FashionForm;

use App\Fashion;
use RestRose\Water\Helper;
use RestRose\Water\CropAvatar;
use RestRose\Pipe\Tap;

class FashionController extends Controller
{
    use FormBuilderTrait;

    private $nav;
    private $tap;
    private $helper;

    function __construct()
    {
        $this->tap = new Tap;
        $this->helper = new Helper;

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
        // above : auth

        $records = Fashion::where(function ($query) { 
                                if(!$this->tap->isAdmin()) {
                                    $query->where('fashions.hide', false);
                                    // $query->where('fashions.locked', false);
                                }
                                if(Input::has('key')) {
                                    $query->Where('fashions.name', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('fashions.name_en', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('fashions.set', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('fashions.set_en', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('fashions.content', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('fashions.content_en', 'LIKE', '%'.Input::get('key').'%');
                                }
                            })
                          ->leftJoin('config', 'fashions.type', '=', 'config.id')
                          ->leftJoin('config as g', 'fashions.gender', '=', 'g.id')
                          ->select('fashions.*', 'config.text as type_name', 'g.text as gender_name')
                          ->paginate(40);
        $conf = ['records' => $records, 'nav' => $this->nav];
        if(Input::has('key')) $conf = array_add($conf, 'key', Input::get('key'));

        return view('shop.fashion')->with('conf', $conf);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $form = $this->form(FashionForm::class, [
            'method' => 'POST',
            'url' => route('fashion.store')
        ]);

        return view('form', compact('form'))->with('conf',['title'=>'添加新款', 'icon'=>'shopping-bag', 'nav'=>$this->nav]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $form = $this->form(FashionForm::class);
        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        $input = $request->all();
       
        $input['created_by'] = $this->tap->getSessionId();
        //print_r($input);

        $new_id =  Fashion::create($input)->id;

        $conf = [
                'class'=>'success', 
                'title'=>'添加成功', 
                'content'=>'您添加的款式已成功, 但如果不完成添加图片的, 款式将不会公布', 
                'button'=>[
                    ['name'=>'设置图片', 'href'=>'/fashion/show/'.$new_id],
                    ['name'=>'不设置继续添加', 'href'=>'/fashion/create'],
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

        $record = Fashion::leftJoin('config', 'fashions.type', '=', 'config.id')
                         ->leftJoin('config as g', 'fashions.gender', '=', 'g.id')
                         ->select('fashions.*', 'config.text as type_name', 'g.text as gender_name')
                         ->find($id);
        if(!count($record)) die('FashionController/show: bad id');

        return view('shop.fashion-show')->with('conf', ['record' => $record, 'nav' => $this->nav]);
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

        $record = Fashion::find($id);

        $form = $this->form(FashionForm::class, [
            'method' => 'POST',
            'model' => $record,
            //'url' => route('fashion.update')
            'url' => '/fashion/update/'.$id
        ]);

        return view('form', compact('form'))->with('conf',['title'=>'信息修改', 'icon'=>'shopping-bag', 'nav'=>$this->nav]);

    }

    /**
     *
     *
     */
    public function setImage(Request $request, $id) 
    {
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $me = Fashion::find($id);
        $path = base_path().'/public/'.$me->img;

        if($me->img != '' && $me->img != null && file_exists($path)) unlink($path);

        $path = 'upload/fashion/'.$id.'-'.date('YmdHis');

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

        $form = $this->form(FashionForm::class);

        $form->validate(['name' => 'required|min:1|max:16']);

        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        $input = $request->all();

        Fashion::find($id)->update($input);

        $conf = [
                'class'=>'success', 
                'title'=>'成功', 
                'content'=>'您已经成功修改信息!', 
                'button'=>[
                    ['name'=>'查看', 'icon'=>'search', 'href'=>'/fashion/show/'.$id]
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

        $target = Fashion::find($id);

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
                    ['name'=>'返回列表', 'href'=>'/fashion']
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

        $target = Fashion::find($id)->update(['locked' => true]);
        return redirect('/fashion/show/'.$id);
    }

    /**
     * lock
     *
     */
    public function unlock($id) 
    {
        if(!$this->tap->isOrgMaster('shop')) return $this->tap->fail();
        // above : auth

        $target = Fashion::find($id)->update(['locked' => false]);
        return redirect('/fashion/show/'.$id);
    }

    /**
     * End
     *
     */
}
