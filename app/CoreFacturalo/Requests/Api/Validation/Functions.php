<?php

namespace App\CoreFacturalo\Requests\Api\Validation;

use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Person;
use App\Models\Tenant\Series;
use App\Models\Tenant\Warehouse;
use Exception;

class Functions
{
    public static function establishment($inputs) {
        $establishment = Establishment::where('code', $inputs['code'])->first();
        
        if ($establishment) {
            return $establishment->id;
        }
        
        throw new Exception("El código ingresado del establecimiento es incorrecto.");
    }

    public static function warehouse($warehouse_description) {
        
        $warehouse_description = $warehouse_description;
        
        $warehouse = Warehouse::updateOrCreate([
            'description' => $warehouse_description
        ], [
            'description' => $warehouse_description
        ]);
        
        return $warehouse->id;
    }
    
    public static function person($inputs, $type) {
        $district_id = $inputs['district_id'];
        $province_id = ($district_id)?substr($district_id, 0 ,4):null;
        $department_id = ($district_id)?substr($district_id, 0 ,2):null;
        
        $person = Person::updateOrCreate([
            'type' => $type,
            'identity_document_type_id' => $inputs['identity_document_type_id'],
            'number' => $inputs['number'],
        ], [
            'name' => $inputs['name'],
            'trade_name' => $inputs['trade_name'],
            'country_id' => $inputs['country_id'],
            'department_id' => $department_id,
            'province_id' => $province_id,
            'district_id' => $district_id,
            'address' => $inputs['address'],
            'email' => $inputs['email'],
            'telephone' => $inputs['telephone'],
        ]);
        
        return $person->id;
    }
    
    public static function item($inputs) {
        $item = Item::updateOrCreate([
            'internal_id' => $inputs['internal_id'],
        ], [
            'description' => $inputs['description'],
            'item_type_id' => $inputs['item_type_id'],
            'item_code' => $inputs['item_code'],
            'item_code_gs1' => $inputs['item_code_gs1'],
            'unit_type_id' => $inputs['unit_type_id'],
            'currency_type_id' => $inputs['currency_type_id'],
            'sale_unit_price' =>  $inputs['unit_price'],
            'sale_affectation_igv_type_id' => $inputs['affectation_igv_type_id'],
            'purchase_affectation_igv_type_id' => $inputs['affectation_igv_type_id'],
        ]);
        
        return $item->id;
    }
    
    public static function findAffectedDocumentByExternalId($external_id) {
        $document = Document::where('external_id', $external_id)
            ->first();
        
        if (!$document) throw new Exception("No se encontró el documento con código externo {$external_id}.");
        
        return $document;
    }
    
    public static function voidedDocuments($inputs, $type) {
        if (count($inputs['documents']) === 0) {
            throw new Exception("No se enviaron documentos para la anulación.");
        }
        
        $documents = [];
        
        foreach ($inputs['documents'] as $row) {
            $document = Document::where('external_id', $row['external_id'])
                ->where('date_of_issue', $inputs['date_of_reference'])
                ->where('group_id', ($type === 'summary')?'02':'01')
                ->first();
            
            if (!$document) throw new Exception("El código externo {$row['external_id']} no fue encontrado o la fecha indica no corresponde al documento.");
            
            $documents[] = [
                'document_id' => $document->id,
                'description' => $row['description']
            ];
        }
        
        return $documents;
    }
    
    public static function validateSeries($inputs) {
        $series = Series::where('number', $inputs['series'])
            ->where('document_type_id', $inputs['document_type_id'])
            ->where('establishment_id', $inputs['establishment_id'])
            ->first();
        
        if (!$series) {
            throw new Exception("La serie ingresada {$inputs['series']}, es incorrecta.");
        }
    }
    
    public static function DNI($inputs){
        if (($inputs['document_type_id'] == '03') && ($inputs['total']) > 700) {
            $person = Person::query()
                ->with('identity_document_type')
                ->find($inputs['customer_id']);
            
            if (!in_array($person->identity_document_type_id, ['01'])) throw new Exception("El tipo doc. identidad {$person->identity_document_type->description} del cliente no es valido.");
        }
    }
}