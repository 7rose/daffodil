<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Http\Forms\Tap\DepartmentForm;

use App\Department;
use RestRose\Pipe\Tap;

class ShopController extends Controller
{
    use FormBuilderTrait;

    private $nav;
    private $tap;
    private $me;

    function __construct()
    {
        $this->tap = new Tap;
        $this->me = $this->tap->fullSQL();

        $this->nav = [['href'=>'/panel', 'link'=>'面版'], ['href'=>'/shop', 'link'=>'门店'], ['href'=>'/shop/create?type=shop', 'link'=>'添加']];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parent_id = Department::where('is_shop', true)->first()->id;

        $records = DB::table('departments')
                     ->where('departments.parent_id', $parent_id)
                     ->leftJoin('staff', 'departments.id', '=', 'staff.department')
                     ->select(DB::raw('group_concat(staff.name) as staff_name, group_concat(staff.id) as staff_id, group_concat(staff.img) as staff_img,  group_concat(staff.gender) as staff_gender'), 'departments.name', 'departments.id')
                     ->groupBy('departments.id')
                     ->get();

        return view('shop.main',['records'=>$records, 'nav'=>$this->nav]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->form(DepartmentForm::class, [
            'method' => 'POST',
            'url' => route('shop.store')
        ]);

        $title = '开店';

        return view('form', compact('form'))->with('conf',['title'=>$title, 'icon'=>'code-fork', 'nav'=>$this->nav]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = $this->form(DepartmentForm::class);
        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        $input = $request->all();

        $parent = Department::find($input['parent_id']);
        if(!count($parent)) die('ShopController/store: bad parent_id');

        $input['for_org'] = $input['parent_id'];
        $input['level'] = $parent->level + 1;
        $input['created_by'] = $this->me->id;

        $new_id =  Department::create($input)->id;

        $conf = [
                'class'=>'success', 
                'title'=>'添加成功', 
                'content'=>'您添加的门店已成功', 
                'button'=>[
                    ['name'=>'查看', 'href'=>'/shop/show/'.$new_id],
                    ['name'=>'所有门店', 'href'=>'/shop'],
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
        return view('shop.show');
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
