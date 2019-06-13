<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'payment_method_id' => [
                'required',
            ],
            'currency_type_id' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
            'account_id' => [
                'required',
            ],
            'total' => [
                'required'
            ]
        ];
    }
}