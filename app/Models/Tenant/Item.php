<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;

class Item extends ModelTenant
{
    protected $with = ['item_type', 'unit_type', 'currency_type', 'trademark', 'itemCategory', 'warehouses'];
    protected $fillable = [
        'description',
        'item_type_id',
        'internal_id',
        'item_code',
        'item_code_gs1',
        'unit_type_id',
        'currency_type_id',
        'sale_unit_price',
        'purchase_unit_price',
        'icbper',
        'included_igv',
        'has_isc',
        'system_isc_type_id',
        'percentage_isc',
        'suggested_price',

        'sale_affectation_igv_type_id',
        'purchase_affectation_igv_type_id',

        'stock',
        'stock_min',

        'amount_plastic_bag_taxes',

        'trademark_id',
        'item_category_id',

        'attributes',
    ];

    public function getAttributesAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = (is_null($value)) ? null : json_encode($value);
    }

    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function trademark()
    {
        return $this->belongsTo(Trademarks::class);
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    }

    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    public function inventory_kardex()
    {
        return $this->hasMany(InventoryKardex::class);
    }

    public function purchase_item()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function item_warehouse()
    {
        return $this->hasMany(ItemWarehouse::class);
    }

    public function item_price_list()
    {
        return $this->hasMany(ItemPriceList::class);
    }

    public function sale_affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'sale_affectation_igv_type_id');
    }

    public function purchase_affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'purchase_affectation_igv_type_id');
    }

    public function warehouses()
    {
        return $this->hasMany(ItemWarehouse::class)->with('warehouse');
    }

    public function item_unit_types()
    {
        return $this->hasMany(ItemUnitType::class);
    }
}
