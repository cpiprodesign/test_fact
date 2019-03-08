<?php

namespace App\Models\Tenant;


class Pos extends ModelTenant
{
    protected $with = ['establishment', 'user'];
    protected $fillable = [
        'id',
        'user_id',
        'establishment_id',
        'open_amount',
        'close_amount',
        'sales_count',
        'status',
        'ip',
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
