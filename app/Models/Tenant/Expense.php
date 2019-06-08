<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;

class Expense extends ModelTenant
{
    protected $with = ['user', 'currency_type', 'establishment'];
    protected $fillable = [
        'user_id',
        'has_voucher',
        'date_of_issue',
        'description',
        'currency_type_id',
        'total',
        'detail',
        'document_number',
        'detail_voucher',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'establishment');
    }
}