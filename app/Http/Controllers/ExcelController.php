<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Excel;
use App\Offer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ExcelController extends Controller
{
    /**
     * prepare offer
     *
     */
    private function prepareOffer()
    {
        $prepare = Offer::where('offers.hide', false)
                        ->where('offers.locked', false)
                        ->where('offers.sold', false)
                        ->where('offers.need_label', true);
        return $prepare;
    }

    /**
     * export excel for label print
     *
     */
    public function labelPrint()
    {
        $exsists = $this->prepareOffer()->get();
        if(!count($exsists)) return redirect('/offer');
        

        $records = $this->prepareOffer()->leftJoin('goods', 'offers.goods_sn', '=', 'goods.sn')
                            ->leftJoin('config', 'goods.type', '=', 'config.id')
                            ->leftJoin('fashions', 'goods.fashion', '=', 'fashions.id')
                            ->leftJoin('departments', 'offers.shop', '=', 'departments.id')
                            ->select
                                (
                                    'offers.sn', 'offers.weight', 'offers.stone_weight', 'offers.gold_weight', 'offers.ca', 
                                    'goods.name as goods_name', 'goods.price as goods_price', 'goods.stone as goods_stone', 'goods.gold as goods_gold', 'goods.gold_level as gold_level', 
                                    'config.text as type_name', 
                                    'fashions.name as fashion_name', 
                                    'departments.name as shop_name'
                                )
                            ->orderBy('offers.shop')
                            ->orderBy('offers.sn')
                            ->get();
                        //->toArray();
        $data = [];
        $title = [ '名称', '类别', '价格', '编号', '说明', '证书', '店面'];
        array_push($data, $title);

        foreach ($records as $row) {
            $one = [];
            array_push($one, $row->fashion_name.'-'.$row->goods_name);
            array_push($one, $row->type_name);
            array_push($one, $row->goods_price);
            array_push($one, $row->sn);

            $tmp = [];
            if(floatval($row->weight) != 0 && $row->weight != null){
                array_push($tmp, '总重:'.floatval($row->weight).'g');
            } 
            if($row->goods_stone != '' && $row->goods_stone != null){
                $stone = $row->goods_stone;
                $stone .=  floatval($row->stone_weight) != 0 ? floatval($row->stone_weight).'ct.' : '';
                array_push($tmp, $stone);
            }
            if($row->goods_gold != '' && $row->goods_gold != null){
                $gold = $row->gold_level ? $row->gold_level : '';
                $gold .= $row->goods_gold;
                $gold .= floatval($row->gold_weight) != 0 ? floatval($row->gold_weight).'g' : '';
                array_push($tmp, $gold);
            }
            $content = implode(';', $tmp);

            array_push($one, $content);
            array_push($one, $row->ca);
            array_push($one, $row->shop_name != '' && $row->shop_name != null ? $row->shop_name : '库存');

            array_push($data, $one);
        }
        // export
        $name = date("y-m-d",time()).'-'.time().'_label';

        $this->prepareOffer()->update(['need_label' => false]);

        Excel::create($name,function($excel) use ($data){
            $excel->sheet('products', function($sheet) use ($data){
                $sheet->setAutoSize(true);
                $sheet->freezeFirstRow();
                $sheet->rows($data);
            });
        })->export('xlsx');
    }

    /**
     * end
     *
     */
}
