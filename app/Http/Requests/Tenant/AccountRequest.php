<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'account_type_id' => [
                'required',
            ],
            'name' => [
                'required',
            ],
            'currency_type_id' => [
                'required',
            ],
            'date_of_issue' => [
                'required'
            ],
            'beginning_balance' => [
                'required', 'numeric'
            ]
        ];
    }
}