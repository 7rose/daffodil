<?php

namespace App\Http\Forms\Shop;

use Request;
use Kris\LaravelFormBuilder\Form;
use Input;
use Session;

use RestRose\Water\Helper;
use RestRose\Pipe\Tap;

use App\Fashion;

class OffersForm extends Form
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

            // master of shops
            if($tap->isOrgMaster('shop')) {
                $this->add('shop', 'choice', [
                    'label' => '所属门店', 
                    'selected' => Session::has('lock_shop') ? Session::get('lock_shop') : null,
                    'empty_value' => '-- 未分配的 --',
                    'choices'=> $tap->listOf('shop')
                ])
                ->add('lock_shop', 'checkbox', [
                    'label' => '锁定门店',
                    'value' => 1,
                    'checked' => Session::has('lock_shop') ? true : false
                ]);

            }elseif($tap->inOrg('shop')) {
                $this->add('shop', 'hidden', [
                    'value' => $tap->getHeadDepartment()
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
                'label' => '名称',
                'value' => count($has) ? $has->name : null,
                'rules' => 'required|min:1|max:16'
            ])
            ->add('name_en', 'text', [
                'label' => 'English Name',
                'value' => count($has) ? $has->name_en : null,
                'rules' => 'min:1|max:16'
            ])
            ->add('gold', 'text', [
                'label' => '金',
                'rules' => 'min:1|max:16'
            ])
            ->add('gold_level', 'text', [
                'label' => '等级',
                'rules' => 'min:1|max:16'
            ])
            ->add('gold_weight', 'number', [
                'label' => '重量(g)',
                'attr' =>['step' => 0.001]
            ])
        	->add('stone', 'text', [
                'label' => '主石',
                'rules' => 'min:1|max:16'
            ])
            ->add('stone_weight', 'number', [
                'label' => '主石-重量(clt)',
                'attr' =>['step' => 0.001]
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
