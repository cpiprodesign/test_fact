<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DispatchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'establishment_id' => [
                'required',
            ],
            'document_type_id' => [
                'required',
            ],
            'series' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
            'customer_id' => [
                'required',
            ],
            'observations' => [
                'required',
            ],
            'transport_mode_type_id' => [
                'required',
            ],
            'transfer_reason_type_id' => [
                'required',
            ],
            'port_code' => [
                'numeric',
                'nullable'
            ],
            'container_number' => [
                'numeric',
                'nullable'
            ],
            'transfer_reason_description' => [
                'required',
            ],
            'date_of_shipping' => [
                'required',
            ],
            'transshipment_indicator' => [
                'required',
            ],
            'unit_type_id' => [
                'required',
            ],
            'total_weight' => [
                'required',
            ],
            'packages_number' => [
                'required',
            ],
            'license_plate' => [
                'required',
            ],        
        ];
    }
}