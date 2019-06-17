<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request)
    {
        //$sale_unit_price = $this->formatNumber($this->sale_unit_price);
        //$purchase_unit_price = $this->formatNumber($this->purchase_unit_price);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'principal' => $this->principal,
            'active' => $this->active,
            'value' => $this->value,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
