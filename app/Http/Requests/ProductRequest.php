<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:10',
            'name_en' => 'min:2|max:16',
            'type' => 'required|min:1|max:10',
            'content' => 'required|min:2|max:100',
            'shop' => 'required|min:1|max:10',
            'price' => 'required',
        ];
    }
}
