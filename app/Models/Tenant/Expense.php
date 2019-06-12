<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;

class Expense extends ModelTenant
{
    protected $with = ['user', 'currency_type'];
    protected $fillable = [
        'user_id',
        'has_voucher',
        'date_of_issue',
        'description',
        'currency_type_id',
        'total',
        'detail',
        'company_number',
        'detail_voucher',
    ];

    protected $casts = [
        'detail_voucher' => 'array'
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

    public function getDetailVoucher($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }


}