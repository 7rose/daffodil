<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Cookie;
use Hash;
use Session;
use Input;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Http\Forms\Staff\LoginForm;
use App\Http\Forms\Staff\StaffForm;

use App\Department;
use App\Staff;
use RestRose\Water\Helper;
use RestRose\Water\CropAvatar;
use RestRose\Pipe\Tap;
use RestRose\Wechat\Publics\Qrcode;

class StaffController extends Controller
{
    use FormBuilderTrait;

    private $nav;
    private $tap;
    private $helper;

    private $login_id;

    function __construct()
    {
        $this->tap = new Tap;
        $this->helper = new Helper;

        $this->nav = [['href'=>'/panel', 'link'=>'面版'], ['href'=>'/staffing', 'link'=>'成员']];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->tap->independentOrg()) return $this->tap->fail();
        // above : auth

        $records = Staff::where(function ($query) { 
                            // admin
                            if(!$this->tap->realRoot()) $query->where('staff.id', '>', 1);
                            if(!$this->tap->isAdmin()) {
                                $query->where('staff.hide', false);
                                $query->whereIn('staff.department', $this->tap->allVisibleDepartments());
                            }

                            // seek
                            if(Input::has('key')) {
                                $query->Where('staff.name', 'LIKE', '%'.Input::get('key').'%');
                                $query->orWhere('staff.name_en', 'LIKE', '%'.Input::get('key').'%');
                                $query->orWhere('staff.sn', 'LIKE', '%'.Input::get('key').'%');
                                $query->orWhere('staff.mobile', 'LIKE', '%'.Input::get('key').'%');
                                $query->orWhere('staff.content', 'LIKE', '%'.Input::get('key').'%');
                                $query->orWhere('staff.content_en', 'LIKE', '%'.Input::get('key').'%');
                            }
                        })
                        // visible departments
                        // ->whereIn('staff.department', $this->tap->allVisibleDepartments())
                        ->leftJoin('staff as c', 'staff.created_by', '=', 'c.id')
                        ->leftJoin('config', 'staff.gender', '=', 'config.id')
                        ->leftJoin('departments', 'staff.department', '=', 'departments.id')
                        ->leftJoin('departments as d', 'staff.org', '=', 'd.id')
                        ->leftJoin('positions', 'staff.position', '=', 'positions.id')
                        ->select('staff.*', 'c.name as create_name', 'config.text as gender_name', 'departments.name as department_name', 'd.name as org_name', 'departments.level as department_level', 'positions.name as position_name', 'positions.show as position_show')
                        ->orderBy('staff.org')
                        ->orderBy('positions.level')
                        ->orderBy('departments.level')

                        //->groupBy('staff.org')
                        ->paginate(30);

        $conf = ['records' => $records, 'nav' => $this->nav];
        if(Input::has('key')) $conf = array_add($conf, 'key', Input::get('key'));

        return view('staff.staffing')->with('conf', $conf);

    }
    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function autoLogin()
    {
        return redirect('/panel');
    }


    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        $form = $this->form(LoginForm::class, [
            'method' => 'POST',
            'url' => route('login.check')
        ]);

        return view('form', compact('form'))->with('conf',['title'=>'身份验证', 'icon'=>'key']);
    }


    /**
     * Check Login Info
     *
     */
    public function check(Request $request)
    {
        $form = $this->form(LoginForm::class);
        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        $input_id = $request->id;

        if($this->helper->isMobile($input_id)){
            //mobile
            $mobile = Staff::where('mobile', $input_id)->first();
            if(count($mobile)){
                if(!$mobile->mobile_confirmed) {
                    $errors = ['手机号未经验证,不可用于登录,请使用编号/密码方式或者使用微信自动认证登录'];
                    return redirect()->back()->withInput()->withErrors($errors);
                }else{
                    $this->login_id = $mobile;
                }

            }else{
                $errors = ['手机号不存在'];
                return redirect()->back()->withInput()->withErrors($errors);
            }

        }else{
            //SN
            $me = Staff::where('sn',$input_id)->first();
            if(count($me)) {
                $this->login_id = $me;
            }else{
                $errors = ['编号不存在'];
                return redirect()->back()->withInput()->withErrors($errors);
            }
        }

        $input_password = $request->password;
        $password = $this->login_id->password;

        if (Hash::check($input_password, $password)) {
            $id = $this->login_id->id;
            $this->setSession($id);
            $redirect = $request->path == 'login' ? '/panel' : '/'.$request->path;
            return redirect($redirect);
        }else{
            $errors = ['密码错误'];
            return redirect()->back()->withInput()->withErrors($errors);
        }
    }

    // set Session
    private function setSession($id)
    {
        if(!Session::has('id')) Session::put('id', $id);
    }

    /**
     * Logout
     *
     */
    public function Logout()
    {
        Session::flush();
        if (Cookie::has('id')) Cookie::queue('id', '', -1);
        return redirect('/panel');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->form(StaffForm::class, [
            'method' => 'POST',
            'url' => route('staff.store')
        ]);

        $title = '成员添加';

        if(Input::has('dp')){
            $target = Department::find(Input::get('dp'));
            if(count($target)) $title .= ' - '.$target->name;
        }

        return view('form', compact('form'))->with('conf',['title'=>$title, 'icon'=>'user-plus', 'nav'=>$this->nav]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = $this->form(StaffForm::class);
        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        if(!$this->helper->isMobile($request->mobile)) {
            $errors = [' 手机号格式错误'];
            return redirect()->back()->withInput()->withErrors($errors);
        }

        if(!$this->tap->fit($request->department, $request->position)) {
            $errors = [' 部门和职位不匹配!'];
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $all = $request->all();

        $all['sn'] = $this->tap->getSN($all['department']);
        $all['org'] = $this->tap->getOrg($all['department']);

        if((array_has($all, 'admin') || array_has($all, 'root')) && !$this->tap->inSameOrg($all['department'])) {
            $errors = [' 不能在其他组织(即使为下属机构)创建管理员!'];
            return redirect()->back()->withInput()->withErrors($errors);
        }


        $all['password'] = bcrypt(mb_substr(strval($all['mobile']), 5));
        $all['created_by'] = $this->tap->getSessionId();

        $new_id =  Staff::create($all)->id;

        $conf = [
                'class'=>'success', 
                'title'=>'添加成功', 
                'content'=>'您添加的成员已成功', 
                'button'=>[
                    ['name'=>'查看', 'href'=>'/staff/show/'.$new_id],
                    ['name'=>'继续添加', 'icon'=>'user-plus', 'href'=>'/staff/create'],
                    ['name'=>'成员管理', 'href'=>'/staffing'],
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
    public function show($id=0)
    {
        $target = $this->tap->chooseId($id);

        $record = Staff::leftJoin('departments', 'staff.department', '=', 'departments.id')
                       ->leftJoin('positions', 'staff.position', '=', 'positions.id')
                       ->leftJoin('config', 'staff.gender', '=', 'config.id')
                       ->select('staff.*', 'departments.name as department_name', 'positions.name as position_name', 'positions.show as position_show', 'config.text as gender_name')
                       ->find($target);

        return view('staff.staff-show')->with('conf', ['record' => $record, 'nav' => $this->nav]);
    }

    /**
     * Change Icon
     *
     *
     */
    //public function setImage($id)
    public function setImage(Request $request, $id)
    {
        //$id = $this->tap->getSessionId();
        if(!$this->tap->isSelf($id)) return $this->tap->fail();
        // above : auth

        $me = Staff::find($id);
        $path = base_path().'/public/'.$me->img;

        if($me->img != '' && $me->img != null && file_exists($path)) unlink($path);

        $path = 'upload/personal/'.$id.'-'.date('YmdHis');

        //$crop = new CropAvatar($_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file'], 400, 400, $path);
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
        if(!$this->tap->hasRights($id)) return $this->tap->fail();
        // above : auth

        $record = Staff::find($id);

        $form = $this->form(StaffForm::class, [
            'method' => 'POST',
            'model' => $record,
            //'url' => route('fashion.update')
            'url' => '/staff/update/'.$id
        ]);

        return view('form', compact('form'))->with('conf',['title'=>'信息修改', 'icon'=>'user', 'nav'=>$this->nav]);
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
        if(!$this->tap->hasRights($id)) return $this->tap->fail();
        // above : auth

        $form = $this->form(StaffForm::class);

        $form->validate(['mobile' => 'required']);

        if (!$form->isValid()) return redirect()->back()->withErrors($form->getErrors())->withInput();

        if(!$this->helper->isMobile($request->mobile)) {
            $errors = [' 手机号格式错误'];
            return redirect()->back()->withInput()->withErrors($errors);
        }

        if(!$this->tap->fit($request->department, $request->position)) {
            $errors = [' 部门和职位不匹配!'];
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $input = $request->all();

        Staff::find($id)->update($input);

        $conf = [
                'class'=>'success', 
                'title'=>'成功', 
                'content'=>'您已经成功修改信息!', 
                'button'=>[
                    ['name'=>'查看', 'icon'=>'search', 'href'=>'/staff/show/'.$id]
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
        if(!$this->tap->hasRights($id)) return $this->tap->fail();
        // above : auth

        $target = Staff::find($id)->update(['locked' => true]);
        return redirect('/staff/show/'.$id);
    }

    /**
     * lock
     *
     */
    public function unlock($id) 
    {
        if(!$this->tap->hasRights($id)) return $this->tap->fail();
        // above : auth

        $target = Staff::find($id)->update(['locked' => false]);
        return redirect('/staff/show/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if(!$this->tap->hasRights($id)) return $this->tap->fail();
        // above : auth

        $target = Staff::find($id);

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
                    ['name'=>'返回列表', 'href'=>'/staffing']
                ],
            ];

        return view('note')->with('conf',$conf);
    }

}











