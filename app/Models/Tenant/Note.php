<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\NoteCreditType;
use App\Models\Tenant\Catalogs\NoteDebitType;

class Note extends ModelTenant
{
    protected $with = ['affected_document', 'note_credit_type', 'note_debit_type'];
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'note_type',
        'note_credit_type_id',
        'note_debit_type_id',
        'note_description',
        'affected_document_id',
    ];

    public function document()
    {
        return $this->hasOne(Document::class);
    }

    public function affected_document()
    {
        return $this->belongsTo(Document::class, 'affected_document_id');
    }

    public function note_credit_type()
    {
        return $this->belongsTo(NoteCreditType::class, 'note_credit_type_id');
    }

    public function note_debit_type()
    {
        return $this->belongsTo(NoteDebitType::class, 'note_debit_type_id');
    }
}