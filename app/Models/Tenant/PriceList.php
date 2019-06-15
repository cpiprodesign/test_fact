<?php

namespace App\Models\Tenant;

class PriceList extends ModelTenant
{
    protected $table = 'price_list';

    protected $fillable = [
        'name',
        'type',
        'principal',
        'active',
        'value',
    ];
}