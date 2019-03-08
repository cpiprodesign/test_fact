<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'name' => [
                'required', 
            ],
            'pricing' => [
                'required',
                'numeric' ,
                'min:0'  
            ],
            'limit_users' => [
                'required',
                'numeric',
                'integer',
                'min:1'  
            ],
            'limit_documents' => [
                'required',
                'numeric' ,
                'integer',
                'min:1'  
            ],
            'plan_documents' => [
                'required'
            ],
        ];
    }
}