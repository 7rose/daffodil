<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;
use App\Product;
use Session;
use Excel;
use Image;
use Input;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recs = Product::orderBy('updated_at', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('product', ['recs'=>$recs]);
    }

    public function excel()
    {
        $recs = Product::get();

        $data_array = [['名称', '英文名', '类别', '价格', '编号', '店面', '证书', '说明']];

        if(count($recs)){
            foreach ($recs as $rec) {
                $tmp_array = [];
                $tmp_array[] = $rec->name;
                $tmp_array[] = $rec->name_en;
                $tmp_array[] = $rec->type;
                $tmp_array[] = floatval($rec->price);
                $tmp_array[] = $rec->sn;
                $tmp_array[] = $rec->shop;
                $tmp_array[] = $rec->ca;
                $tmp_array[] = $rec->content;

                $data_array[] = $tmp_array;
            }
        }

        $name = date("Y-m-d-H-i",time()).'_products';

        Excel::create($name,function($excel) use ($data_array){
            $excel->sheet('products', function($sheet) use ($data_array){
                $sheet->setAutoSize(true);
                $sheet->freezeFirstRow();
                $sheet->rows($data_array);
            });
        })->export('xls');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('input');
    }

    /**
     * 编辑
     *
     */
    public function edit($id)
    {
        $old = Product::find($id);

        return view('input', ['old'=>$old]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\ProductRequest $request, $id)
    {
        $rec = Product::find($id);
        $update = $request->all();
        unset($update['_token']);
        unset($update['amount']);
        Product::where('id', $id)->update($update);
        return redirect('/product');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ProductRequest $request)
    {
        $input = $request->all();
        $sn_max = Product::max('sn');
        $sn = $sn_max < 1000 ? 1000 : $sn_max+1;
        $input['sn'] = $sn;
        $input['state'] = '正常';
        $input['create_by'] = Session::get('id');
        Product::create($input);
        return redirect('/product');
    }

    /**
     * Upload Image
     *
     * @param  int  $id
     * @return Image
     */
    public function show(Requests $request)
    {
        // $input = $request->all();
        // $base64_body = substr(strstr($base64,','),1);
        // $png= base64_decode($base64_body);

        // $png_name = $target->work_id.'-'.time().'.png';
        // $base_path_img =  base_path().'/public/upload/member/';
        // $path = $base_path_img.$png_name;

        

        // //if($target->img != '' && $target->img != null && Storage::exists($base_path_img.$target->img)) unlink($base_path_img.$target->img);
        
        // file_put_contents($path, $png);
        // resizing an uploaded file
        Image::make(Input::file('photo'))->save('upload/personal/foo.jpg');
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
