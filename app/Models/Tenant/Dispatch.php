<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\TransferReasonType;
use App\Models\Tenant\Catalogs\TransportModeType;
use App\Models\Tenant\Catalogs\UnitType;

class Dispatch extends ModelTenant
{
    protected $with = ['user', 'soap_type', 'state_type', 'document_type', 'unit_type', 'transport_mode_type',
                       'transfer_reason_type', 'items'];

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'document_type_id',
        'series',
        'number',
        'date_of_issue',
        'time_of_issue',
        'customer_id',
        'customer',
        'observations',
        'transport_mode_type_id',
        'transfer_reason_type_id',
        'transfer_reason_description',
        'date_of_shipping',
        'transshipment_indicator',
        'port_code',
        'unit_type_id',
        'total_weight',
        'packages_number',
        'container_number',
        'origin',
        'delivery',
        'dispatcher',
        'driver',
        'license_plate',

        'legends',

        'filename',
        'hash',

        'has_xml',
        'has_pdf',
        'has_cdr'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'date_of_shipping' => 'date',
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

    public function getOriginAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setOriginAttribute($value)
    {
        $this->attributes['origin'] = (is_null($value))?null:json_encode($value);
    }

    public function getDeliveryAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDeliveryAttribute($value)
    {
        $this->attributes['delivery'] = (is_null($value))?null:json_encode($value);
    }

    public function getDispatcherAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDispatcherAttribute($value)
    {
        $this->attributes['dispatcher'] = (is_null($value))?null:json_encode($value);
    }

    public function getDriverAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDriverAttribute($value)
    {
        $this->attributes['driver'] = (is_null($value))?null:json_encode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function unit_type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function transport_mode_type()
    {
        return $this->belongsTo(TransportModeType::class, 'transport_mode_type_id');
    }

    public function transfer_reason_type()
    {
        return $this->belongsTo(TransferReasonType::class, 'transfer_reason_type_id');
    }

    public function items()
    {
        return $this->hasMany(DispatchItem::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->series.'-'.$this->number;
    }

    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'dispatch', 'type' => 'xml', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'dispatch', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'dispatch', 'type' => 'cdr', 'external_id' => $this->external_id]);
    }
}