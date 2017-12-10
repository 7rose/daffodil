<?php

namespace App\Http\Forms\Item;

use Kris\LaravelFormBuilder\Form;

use RestRose\Water\Helper;

class ItemForm extends Form
{
    public function buildForm()
    {
    	$helper = new Helper;

        $this->add('type', 'choice', [
                'label' => '类别', 
                'rules' => 'required',
                // 'selected' => count($has) ? $has->type : null,
                'empty_value' => '-- 选择 --',
                'choices'=> $helper->getConfigList('lwj_type')
               ])

        	 ->add('unit', 'choice', [
                'label' => '单位', 
                'rules' => 'required',
                // 'selected' => count($has) ? $has->type : null,
                'empty_value' => '-- 选择 --',
                'choices'=> $helper->getConfigList('lwj_unit')
               ])

        	 ->add('title', 'text', [
                'label' => '名称',
                'rules' => 'required|min:1|max:16'
               ])

        	 ->add('summary', 'text', [
                'label' => '简介',
                'rules' => 'required|min:1|max:16'
               ])

        	 ->add('price', 'number', [
                'label' => '单价',
                'attr' =>['step' => 0.01],
                'rules' => 'required'
            	])

        	 ->add('num', 'number', [
                'label' => '数量',
                'attr' =>['step' => 0.01],
                'rules' => 'required'
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
            
