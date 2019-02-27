<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuotationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'customer_id' => [
                'required',
            ],
            'establishment_id' => [
                'required',
            ],
            'series' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
            'exchange_rate_sale' => [
                'required',
                'numeric',
                'min:0.01'
            ],
        ];
    }
}