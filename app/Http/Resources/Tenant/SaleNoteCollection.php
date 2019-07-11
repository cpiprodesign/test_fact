<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Pos;

class SaleNoteCollection extends ResourceCollection
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
            $has_pdf = true;
            
            $has_delete = true;

            $payments = Payment::where('sale_note_id', $row->id)->get();

            if(count($payments) > 0)
            {
                foreach($payments as $payment)
                {
                    $pos = Pos::find($payment->pos_id);

                    if(is_null($pos))
                    {
                        $has_delete = false;
                    }
                }
            }

            return [
                'id' => $row->id,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'number' => $row->number_full,
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->number,
                'currency_type_id' => $row->currency_type_id,
                'total_exportation' => $row->total_exportation,
                'total_free' => $row->total_free,
                'total_unaffected' => $row->total_unaffected,
                'total_exonerated' => $row->total_exonerated,
                'total_taxed' => $row->total_taxed,
                'total_igv' => $row->total_igv,
                'total' => $row->total,
                'total_paid' => $row->total_paid,
                'total_to_pay' => number_format($row->total - $row->total_paid, 2, ".", ""),
                'document_type_description' => $row->document_type->description,
                'has_pdf' => $has_pdf,
                'has_delete' => $has_delete,
                'download_pdf' => $row->download_external_pdf,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}