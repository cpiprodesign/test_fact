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

            if(is_null($row->document_id))
            {
                $number = $row->sale_note->series.'-'.$row->sale_note->number;
            }
            else
            {
                $number = $row->document->series.'-'.$row->document->number;                
            }

            return [
                'id' => $row->id,
                'customer' => $row->customer->name,
                'number' => $number,
                'date_of_issue' => $row->date_of_issue,
                'account' => $row->account->name,
                'payment_method' => $row->payment_method->description,
                'total' => "{$row->currency_type->symbol} {$row->total}"
            ];
        });
    }
}