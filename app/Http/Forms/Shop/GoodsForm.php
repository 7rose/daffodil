<?php

namespace App\Http\Forms\Shop;

use Request;
use Kris\LaravelFormBuilder\Form;
use Input;
use Session;

use RestRose\Water\Helper;
use RestRose\Pipe\Tap;

use App\Fashion;

class GoodsForm extends Form
{
    public function buildForm()
    {
        $helper = new Helper;
        $tap = new Tap;

        $has = Fashion::find(Input::get('fashion'));
            // extends fashion
            if(Input::has('fashion') && count($has)) {
                $this->add('num', 'number', [
                    'label' => '数量',
                    'value' => 1,
                    'rules' => 'min:1'
                ])
                ->add('fashion', 'hidden', [
                    'value' => Input::get('fashion')
                ]);
            }

            $this->add('price', 'number', [
                'label' => '价格',
                'attr' =>['step' => 0.01],
                'rules' => 'required'
            ])

            ->add('type', 'choice', [
                'label' => '类别', 
                'rules' => 'required',
                'selected' => count($has) ? $has->type : null,
                'empty_value' => '-- 选择 --',
                'choices'=> $helper->getConfigList('product_type')
            ])
            ->add('gender', 'choice', [
                'label' => '男/女/中性', 
                'selected' => count($has) ? $has->gender : null,
                'rules' => 'required',
                'empty_value' => '-- 选择 --',
                'choices'=> $helper->getConfigList('gender')
            ])
            ->add('name', 'text', [
                'label' => '名称'.(count($has) ? ' :'.$has->name : ''),
                'rules' => 'required|min:1|max:16'
            ])
            ->add('name_en', 'text', [
                'label' => 'English Name'.(count($has) ? ' :'.$has->name_en : ''),
                'rules' => 'min:1|max:16'
            ])
            ->add('gold', 'text', [
                'label' => '贵金属',
                'rules' => 'min:1|max:16'
            ])
            ->add('gold_level', 'text', [
                'label' => '等级',
                'rules' => 'min:1|max:16'
            ])
        	->add('stone', 'text', [
                'label' => '主石',
                'rules' => 'min:1|max:16'
            ])
            ->add('stone_color', 'choice', [
                'label' => '主石-颜色',
                'empty_value' => '-- 选择 --',
                'choices' => $helper->getConfigList('stone_color')
            ])
            ->add('other', 'text', [
                'label' => '其他',
                'rules' => 'min:1|max:32'
            ])
            ->add('content', 'textarea', [
                'label' => '备注',
                'rules' => 'min:2|max:200'
            ])
            ->add('submit','submit',[
                  'label' => '提交',
                  'attr' => ['class' => 'btn btn-success btn-block']
            ]);
    }
}
