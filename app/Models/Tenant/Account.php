<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\AccountType;

class Account extends ModelTenant
{
    protected $with = ['currency_type', 'account_type'];
    protected $fillable = [
        'name',
        'number',
        'currency_type_id',
        'account_type_id',
        'date_of_issue',
        'beginning_balance',
        'current_balance',
        'description',
    ];
   
    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

}