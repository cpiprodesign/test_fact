<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        $type = $this->input('type');
        
        return [
            'number' => [
                'numeric',
                'required',
                "min:8",
                Rule::unique('tenant.persons')->where(function ($query) use($id, $type) {
                    return $query->where('type', $type)
                                 ->where('id', '<>' ,$id);
                })
            ],
            'name' => [
                'required',
                Rule::unique('tenant.persons')->where(function ($query) use($id, $type) {
                    return $query->where('type', $type)
                                 ->where('id', '<>' ,$id);
                })
            ],
            'identity_document_type_id' => [
                'required',
            ],
            'country_id' => [
                'required',
            ],
            'department_id' => [
                'required_if:identity_document_type_id,"066"',
            ],
            'province_id' => [
                'required_if:identity_document_type_id,"066"',
            ],
            'district_id' => [
                'required_if:identity_document_type_id,"066"',
            ],
            'address' => [
                'required_if:identity_document_type_id,"066"',
            ],
            'email' => [
                'nullable',
                'email',
            ]
        ];
    }
}