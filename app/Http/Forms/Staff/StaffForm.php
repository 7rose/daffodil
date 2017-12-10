<?php

namespace App\Http\Forms\Staff;

use Kris\LaravelFormBuilder\Form;
use RestRose\Pipe\Tap;
use Input;

class StaffForm extends Form
{
    public function buildForm()
    {
        $tap = new Tap;
            // extra  
            if($tap->isRoot()) {
                if($tap->realRoot()) {
                    $this->add('root', 'choice', [
                        'choices' => ['0' => '非管理员', '1' => '系统级管理员!!'],
                        'label' => '系统级管理员 ( !! )'
                    ]);
                }
                $this->add('admin', 'choice', [
                    'choices' => ['0' => '非管理员', '1' => '管理员!'],
                    'label' => '管理员 ( ! )'
                ]);
            }

            if($tap->isAdmin()) {
                $this->add('hide', 'choice', [
                    'choices' => ['0' => '显示', '1' => '隐藏'],
                    'label' => '选项:隐藏'
                ]);
                $this->add('locked', 'choice', [
                    'choices' => ['0' => '正常', '1' => '锁定'],
                    'label' => '选项:锁定'
                ]);
            }

            // fix department
            if(Input::has('dp')) {
                // department fixed
                $this->add('department', 'hidden', [
                    'value' => Input::get('dp')
                ])
                ->add('position', 'choice', [
                    'label' => '职位', 
                    'rules' => 'required',
                    'empty_value' => '-- 选择 --',
                    'choices'=> $tap->positionsListFixed(Input::get('dp'), 1)
                ]);

            }else{
                $this->add('department', 'choice', [
                    'label' => '部门', 
                    'rules' => 'required',
                    'empty_value' => '-- 选择 --',
                    'choices'=> $tap->departmentList()
                ])
                ->add('position', 'choice', [
                    'label' => '职位', 
                    'rules' => 'required',
                    'empty_value' => '-- 选择 --',
                    'choices'=> $tap->positionList(0, 1)
                ]);
            }
        
            
            $this->add('gender', 'choice', [
                'label' => '性别', 
                'empty_value' => '-- 选择 --',
                'choices'=> ['1'=>'男', '2'=>'女']
            ]);

            

            $this->add('name', 'text', [
                'label' => '姓名',
                'rules' => 'required|min:2|max:16'
            ])
            ->add('mobile', 'number', [
                'label' => '手机号',
                'rules' => 'required|unique:staff'
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