<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    private $decimal;

    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            return [
                'id' => $row->id,
                'name' => $row->name,
                'number' => $row->number,
                'description' => $row->description,
                'current_balance' => "{$row->currency_type->symbol} {$row->current_balance}"
            ];
        });
    }
}