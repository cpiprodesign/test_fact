<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        
        return [
            'supplier_id' => [
                'required',
            ],
            'establishment_id' => [
                'required',
            ],
            'warehouse_id' => [
                'required',
            ],
            'number' => [
                'required',
                'numeric'
            ],
            'series' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ], 
        ];
    }
}
