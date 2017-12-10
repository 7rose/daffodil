<?php

namespace App\Http\Forms\Card;

use Kris\LaravelFormBuilder\Form;
//use Input;
use Request;

class CardFaceForm extends Form
{
    public function buildForm()
    {
        $this->add('ratio', 'number', [
                'label' => '折扣率(0 ~ 100)',
                // 'attr' =>['step' => 0.01],
                'rules' => 'required|min:0|max:99'
            	])

	        ->add('title', 'text', [
	                'label' => '标题',
	                'rules' => 'required|min:1|max:16'
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
