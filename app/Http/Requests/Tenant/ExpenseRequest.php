<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'has_voucher' => [
                'required',
            ],
            'date_of_issue' => [
                'required'
            ],
            'description' => [
                'required',
            ],
            'currency_type_id' => [
                'required',
            ],
            'total' => [
                'required', 'numeric'
            ],
            // 'company_number' => [
            //     'numeric'
            // ],
        ];
    }
}