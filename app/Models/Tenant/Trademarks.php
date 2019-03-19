<?php

namespace App\Models\Tenant;


class Trademarks extends ModelTenant
{
    protected $table = 'trademarks';

    protected $fillable = [
        'name',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

}
