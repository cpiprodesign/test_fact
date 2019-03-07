<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class ExchangeRate extends ModelTenant
{
    use UsesTenantConnection;

    protected $fillable = [
        'date',
        'buy',
        'sell',
        'date_original',
        'purchase',
        'purchase_original',
        'sale',
        'sale_original',
    ];
}