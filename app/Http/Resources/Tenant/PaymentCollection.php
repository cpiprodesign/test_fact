<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentCollection extends ResourceCollection
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
                'customer' => $row->customer->name,
                'serie' => $row->document->series,
                'number' => $row->document->number,
                'date_of_issue' => $row->date_of_issue,
                'account' => $row->account->name,
                'payment_method' => $row->payment_method->description,
                'total' => "{$row->currency_type->symbol} {$row->total}"
            ];
        });
    }
}