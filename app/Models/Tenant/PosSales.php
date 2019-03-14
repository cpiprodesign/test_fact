<?php

namespace App\Models\Tenant;


class PosSales extends ModelTenant
{
    protected $with = ['document','details'];
    protected $fillable = [
        'document_id',
        'pos_id',
        'total',
        'payed',
        'delta',
    ];


    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function pos()
    {
        return $this->belongsTo(Pos::class);
    }

    public function details()
    {
        return $this->hasMany(PosSalesDetails::class);
    }

}
