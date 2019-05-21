<?php

namespace App\Traits;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Person;
use App\Models\Tenant\Establishment;

/**
 * 
 */
trait ReportTrait
{
    /**
     * Get type doc
     * @param  string $documentType
     * @return int
     */
    public function getTypeDoc($documentType) {
        foreach (DocumentType::all() as $item) {
            if (mb_strtoupper($item->description) == $documentType) return $item->id;
        }
        
        return null;
    }

    public function getPerson($customer) {
        
        $item_split = explode('-', $customer);
        $document = trim($item_split[0]);

        foreach (Person::all() as $item) {
            if ($item->number == $document) return $item->id;
        }
        
        return null;
    }

    public function getEstablishment($establishment) {
        
        foreach (Establishment::all() as $item) {
            if ($item->description == $establishment) return $item->id;
        }
        
        return null;
    }
}