<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\PaymentMethod;

class Payment extends ModelTenant
{
    protected $with = ['currency_type'];

    protected $fillable = [
        'document_id',
        'sale_note_id',
        'customer_id',
        'payment_method_id',
        'date_of_issue',
        'currency_type_id',
        'account_id',
        'description',
        'total',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class, 'sale_note_id');
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function customer()
    {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}