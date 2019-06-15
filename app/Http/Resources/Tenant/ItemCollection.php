<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Tenant\Configuration;

class ItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    private $decimal;

    public function formatNumber($value)
    {
        $decimal = Configuration::first()->decimal;
        return number_format($value, $decimal);
    }

    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            $sale_unit_price = $this->formatNumber($row->sale_unit_price);
            $purchase_unit_price = $this->formatNumber($row->purchase_unit_price);

            return [
                'id' => $row->id,
                'unit_type_id' => $row->unit_type_id,
                'description' => $row->description,
                'internal_id' => $row->internal_id,
                'item_code' => $row->item_code,
                'item_code_gs1' => $row->item_code_gs1,
                'stock' => $row->stock,
                'stock_min' => $row->stock_min,
                'sale_unit_price' => "{$row->currency_type->symbol} {$sale_unit_price}",
                'purchase_unit_price' => "{$row->currency_type->symbol} {$purchase_unit_price}",
                'included_igv' => (bool) $row->included_igv,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}