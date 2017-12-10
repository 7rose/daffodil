<?php

namespace App\Http\Forms\Shop;

use Request;
use Kris\LaravelFormBuilder\Form;

use RestRose\Water\Helper;
use RestRose\Pipe\Tap;

class FashionForm extends Form
{
    public function buildForm()
    {
        $helper = new Helper;
        $tap = new Tap;

        if($tap->isAdmin()){
            $this
                ->add('hide', 'choice', [
                        'label' => '隐藏',
                        'choices' => ['0' => '显示', '1' => '隐藏']
                ])
                ->add('locked', 'choice', [
                        'label' => '锁定',
                        'choices' => ['0' => '正常', '1' => '锁定']
                ]);
        }

        $this
            ->add('type', 'choice', [
                'label' => '类别', 
                'rules' => 'required',
                'empty_value' => '-- 选择 --',
                'choices'=> $helper->getConfigList('product_type')
            ])
            ->add('gender', 'choice', [
                'label' => '男/女/中性', 
                'rules' => 'required',
                'empty_value' => '-- 选择 --',
                'choices'=> $helper->getConfigList('gender')
            ])
            ->add('name', 'text', [
                'label' => '名称',
                'rules' => 'required|min:1|max:16|unique:fashions'
            ])
            ->add('name_en', 'text', [
                'label' => 'English Name',
                'rules' => 'min:1|max:16'
            ])
        	->add('set', 'text', [
                'label' => '所属套系',
                'rules' => 'min:1|max:16'
            ])
            ->add('set_en', 'text', [
                'label' => 'Set Name',
                'rules' => 'min:1|max:16'
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