<?php

namespace App\Models\Tenant; 


class ItemPriceList extends ModelTenant
{ 
    protected $table = 'item_price_list';

    protected $fillable = [
        'item_id',
        'price_list_id', 
        'value', 
    ];

    public function price_list()
    {
        return $this->belongsTo(Price_List::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}