<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;

class PosCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {
            return [

                'id' => $row->id,
                'user_id' => $row->user_id,
                'user' => $row->user,
                'establishment_id' => $row->establishment_id,
                'establishment' => $row->establishment,
                'open_amount' => $row->open_amount,
                'close_amount' => $row->close_amount,
                'sales_count' => $row->sales_count,
                'balance' => number_format($row->open_amount + $row->close_amount, 2),
                'status' => $row->status,
                'created_at' => Carbon::parse($row->created_at)->format('d/m/Y H:i'),
                'deleted_at' => $row->deleted_at ? Carbon::parse($row->deleted_at)->format('d/m/Y H:i') : null,

            ];
        });
    }
}
