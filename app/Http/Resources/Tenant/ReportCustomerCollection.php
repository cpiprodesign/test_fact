<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportCustomerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            $total = (is_null($row->total)) ? 0:$row->total;
            $total2 = (is_null($row->total2)) ? 0:$row->total2;
            $total_paid = (is_null($row->total_paid)) ? 0:$row->total_paid;
            $total_paid2 = (is_null($row->total_paid2)) ? 0:$row->total_paid2;
            
            return [
                'id' => $row->id,
                'number' => $row->number,
                'name' => $row->name,
                'total' =>  $total + $total2,
                'total_paid' =>  $total_paid + $total_paid2,
                'balance' => ($total + $total2) - ($total_paid + $total_paid2),
                'quantity' => $row->quantity + $row->quantity2
            ];
        });
    }
}