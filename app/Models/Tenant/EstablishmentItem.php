<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;

class EstablishmentItem extends ModelTenant

{
    public $timestamps = false;

    protected $fillable = [
        'establishment_id',
        'item_id',
        'quantity'
    ];

    public function establisment()
    {
        return $this->belongsTo(Establisment::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
