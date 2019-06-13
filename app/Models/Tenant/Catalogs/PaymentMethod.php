<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class PaymentMethod extends ModelCatalog
{
    use UsesTenantConnection;

    protected $table = "cat_payment_methods";
    public $incrementing = false;
}