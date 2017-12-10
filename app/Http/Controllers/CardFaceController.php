<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Http\Forms\Card\CardFaceForm;

use App\Item;
use App\CardFace;

use RestRose\Pipe\Tap;
use RestRose\Water\Helper;

class CardFaceController extends Controller
{
    use FormBuilderTrait;

    function __construct()
    {
        $this->tap = new Tap;
        $this->helper = new Helper;

        $this->nav = [['href'=>'/panel', 'link'=>'面版'], ['href'=>'/card_face', 'link'=>'优惠券']];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = CardFace::leftJoin('items', 'card_face.item_id', '=', 'items.id')
                      ->select('card_face.*', 'items.title as item_title')
                      ->where(function ($query) { 
                                // if(!$this->tap->isAdmin()) {
                                //     $query->where('items.show', true);
                                //     // $query->where('items.locked', false);
                                // }
                                if(Input::has('key')) {
                                    $query->Where('card_face.title', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('card_face.content', 'LIKE', '%'.Input::get('key').'%');
                                    $query->orWhere('items.title', 'LIKE', '%'.Input::get('key').'%');
                                }
                            })
                      ->paginate(40);

        $conf = ['records' => $records, 'nav' => $this->nav];
        if(Input::has('key')) $conf = array_add($conf, 'key', Input::get('key'));

        return view('card.card_face')->with('conf', $conf);
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
        $form = $this->form(CardFaceForm::class);
        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        $input = $request->all();
       
        $input['created_by'] = $this->tap->getSessionId();

        // print_r($input);

        $new_id =  CardFace::create($input)->id;

        $conf = [
                'class'=>'success', 
                'title'=>'添加成功', 
                'content'=>'您的操作已成功!', 
                'button'=>[
                    ['name'=>'查看', 'href'=>'/card_face'],
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
        //
    }

    // generate
    public function generate($id)
    {
        $target = Item::findOrFail($id);

        $form = $this->form(CardFaceForm::class, [
            'method' => 'POST',
            'url' => route('card_face.store')
        ]);
        $form->add('item_id', 'hidden', ['value'=>$id]);

        return view('form', compact('form'))->with('conf',['title'=>'添加优惠券('.$target->title.')', 'icon'=>'credit-card', 'nav'=>$this->nav]);
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
