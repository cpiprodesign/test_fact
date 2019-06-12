<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;

class Payment extends ModelTenant
{
    protected $with = ['currency_type'];
    protected $fillable = [
        'document_id',
        'customer_id',
        'payment_method_id',
        'currency_type_id',
        'account_id',
        'description',
        'total',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function customer()
    {
        return $this->belongsTo(Person::class, 'establishment');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'establishment');
    }
}