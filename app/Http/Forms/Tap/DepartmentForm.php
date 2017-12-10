<?php

namespace App\Http\Forms\Tap;

use Kris\LaravelFormBuilder\Form;

use RestRose\Pipe\Tap;
use Input;

class DepartmentForm extends Form
{
    public function buildForm()
    {
        $tap = new Tap;

        if(Input::has('type')) {
            $this->add('parent_id', 'hidden', [
                'value' => $tap->getParentID(Input::get('type'))
            ]);

        }else{
            $this->add('parent_id', 'choice', [
                'label' => '上级部门(机构)', 
                'rules' => 'required',
                'empty_value' => '-- 选择 --',
                'choices'=> $tap->departmentList()
            ]);
        }

        $this->add('name', 'text', [
            'label' => '名称',
            'rules' => 'required|min:2|max:16|unique:departments'
        ])
        ->add('content', 'textarea', [
            'label' => '备注',
            'rules' => 'min:3|max:200'
        ])
        ->add('submit','submit',[
              'label' => '提交',
              'attr' => ['class' => 'btn btn-success btn-block']
        ]);
    }
}
