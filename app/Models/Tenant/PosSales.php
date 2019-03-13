<?php

namespace App\Models\Tenant;


class PosSales extends ModelTenant
{
    protected $with = ['establishment', 'user'];
    protected $fillable = [
        'document_id',
        'pos_id',
        'type',
        'amount',
        'reference',
    ];


    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function pos()
    {
        return $this->belongsTo(Pos::class);
    }

}
