<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Tenant\Pos;

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

            $has_delete = true;

            $pos = Pos::find($row->pos_id);

            if(is_null($pos))
            {
                $has_delete = false;
            }

            return [
                'id' => $row->id,
                'customer' => $row->customer,
                'number' => $row->series.'-'.$row->number,
                'date_of_issue' => $row->date_of_issue,
                'account' => $row->account,
                'payment_method' => $row->payment_method,
                'total' => "{$row->symbol} {$row->total}",
                'has_delete' => $has_delete,
            ];
        });
    }
}