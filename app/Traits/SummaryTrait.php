<?php

namespace App\Traits;

use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\Summary;
use DB;

trait SummaryTrait
{
    public function save($request) {
        $fact = DB::connection('tenant')->transaction(function () use($request) {
            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            return $facturalo;
        });
        
        $fact->senderXmlSignedSummary();
        
        $document = $fact->getDocument();
        
        return [
            'success' => true,
            'message' => "El resumen {$document->identifier} fue creado correctamente",
        ];
    }
    
    public function query($id) {
        $document = Summary::find($id);
        
        $fact = DB::connection('tenant')->transaction(function () use($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->setType('summary');
            $facturalo->statusSummary($document->ticket);
            return $facturalo;
        });
        
        $response = $fact->getResponse();
        
        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }
}
