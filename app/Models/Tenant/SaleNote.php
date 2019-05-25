<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use Illuminate\Support\Facades\DB;

class SaleNote extends ModelTenant
{
    protected $with = ['user', 'document_type', 'currency_type', 'items'];

    protected $fillable = [
        'user_id',
        'establishment_id',
        'establishment',
        //'state_type_id',
        //'group_id',
        'document_type_id',
        'series',
        'number',
        'date_of_issue',
        'time_of_issue',
        'customer_id',
        'customer',
        'currency_type_id',
        //'purchase_order',
        'total_prepayment',
        'total_discount',
        'total_charge',
        'total_exportation',
        'total_free',
        'total_taxed',
        'total_unaffected',
        'total_exonerated',
        'total_igv',
        'total_base_isc',
        'total_isc',
        'total_base_other_taxes',
        'total_other_taxes',
        'total_taxes',
        'total_value',
        'total',

        // 'charges',
        // 'discounts',
        // 'prepayments',
        // 'guides',
        // 'related',
        // 'perception',
        // 'detraction',
        'legends',

        'filename',

        'created_at'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
    }

    public function getAdditionalInformationAttribute($value)
    {
        $arr = explode('|', $value);
        return $arr;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }
    
    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function items()
    {
        return $this->hasMany(SaleNoteItem::class);
    }

    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->series.'-'.$this->number;
    }

    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }

    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'document', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    public static function getItems($sale_note_id)
    {
        $query = DB::connection('tenant')->table('sale_note_items as sni')
          ->select('ite.*')
          ->join('items as ite', 'ite.id', '=', 'sni.item_id')
          ->where('sni.sale_note_id', $sale_note_id)
          ->get();

        $items = $query->transform(function($row) {
            $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;
            $currency_type_symbol = "EN";
            return [
                'id' => $row->id,
                'full_description' => $full_description,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'currency_type_symbol' => $currency_type_symbol,
                'sale_unit_price' => $row->sale_unit_price,
                'purchase_unit_price' => $row->purchase_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id
            ];
        });

        return $items;
    }
}