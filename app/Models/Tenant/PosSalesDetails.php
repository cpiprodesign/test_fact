<?php

namespace App\Models\Tenant;


class PosSalesDetails extends ModelTenant
{
//    protected $with = ['document'];
    protected $fillable = [
        'pos_sales_id',
        'type',
        'amount',
        'reference',
    ];


    public function sale()
    {
        return $this->belongsTo(PosSales::class);
    }

}
