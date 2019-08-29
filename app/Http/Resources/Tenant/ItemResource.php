<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tenant\Configuration;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function formatNumber($value)
    {
        $decimal = Configuration::first()->decimal;
        return number_format($value, $decimal, ".", "");
    }

    public function toArray($request)
    {
        //$sale_unit_price = $this->formatNumber($row->sale_unit_price);
        //$purchase_unit_price = $this->formatNumber($row->purchase_unit_price);

        return [
            'id' => $this->id,
            'description' => $this->description,
            'internal_id' => $this->internal_id,
            'item_code' => $this->item_code,
            'item_code_gsl' => $this->item_code_gsl,
            'currency_type_id' => $this->currency_type_id,
            'sale_unit_price' => $this->formatNumber($this->sale_unit_price),
            'purchase_unit_price' => $this->formatNumber($this->purchase_unit_price),
            'icbper' => (bool) $this->icbper,
            'included_igv' => (bool) $this->included_igv,
            'unit_type_id' => $this->unit_type_id,
            'has_isc' => (bool) $this->has_isc,
            'system_isc_type_id' => $this->system_isc_type_id,
            'percentage_isc' => $this->percentage_isc,
            'suggested_price' => $this->suggested_price,
            'stock' => $this->stock,
            'stock_min' => $this->stock_min,
            'sale_affectation_igv_type_id' => $this->sale_affectation_igv_type_id,
            'purchase_affectation_igv_type_id' => $this->purchase_affectation_igv_type_id,

            'trademark_id' => $this->trademark_id,
            'item_category_id' => $this->item_category_id,
            'item_warehouse' => $this->item_warehouse,
            'item_price_list' => $this->item_price_list,
        ];
    }
}
