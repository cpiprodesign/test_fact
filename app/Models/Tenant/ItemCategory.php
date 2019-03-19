<?php

namespace App\Models\Tenant;


class ItemCategory extends ModelTenant
{
    protected $table = 'item_category';

    protected $with = ['parent', 'childrens'];

//    protected $hidden = ['parent_id'];

    protected $fillable = [
        'description',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(ItemCategory::class, 'id', 'parent_id');
    }

    public function childrens()
    {
        return $this->hasMany(ItemCategory::class, 'parent_id', 'id');
    }

}
