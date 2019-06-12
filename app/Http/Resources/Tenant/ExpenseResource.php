<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request)
    {
        //$sale_unit_price = $this->formatNumber($row->sale_unit_price);
        //$purchase_unit_price = $this->formatNumber($row->purchase_unit_price);

        return [
            'id' => $this->id,
            'has_voucher' => $this->has_voucher,
            'date_of_issue' => $this->date_of_issue,
            'description' => $this->description,
            'currency_type_id' => $this->currency_type_id,
            'total' => $this->total,
            'detail' => $this->detail,
            'company_number' => $this->company_number,
            'detail_voucher' => $this->detail_voucher
            // 'detail_voucher' => $this->getDetailVoucher($this->detail_voucher)
        ];
    }
}
