<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'currency_type_id' => $this->currency_type_id,
            'account_type_id' => $this->account_type_id,
            'date_of_issue' => $this->date_of_issue,
            'beginning_balance' => $this->beginning_balance,
            'description' => $this->description
        ];
    }
}
