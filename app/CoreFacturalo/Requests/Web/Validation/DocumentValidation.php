<?php

namespace App\CoreFacturalo\Requests\Web\Validation;
use App\Models\Tenant\Document;
use App\Models\tenant\Series;

class DocumentValidation
{
    public static function validation($inputs) {
        $document_id = isset($inputs['document_id'])?$inputs['document_id']:null;

        $document = ($document_id !=null)?Document::find($document_id):null;
        if($document !=null){
            if($document->document_type_id == $inputs['document_type_id']){
                $inputs['series_id'] = Series::ByNumber($document->series)->first()->id;
            }

        }

            $series = Functions::findSeries($inputs);
            $inputs['series'] = $series->number;
            unset($inputs['series_id']);



        
        Functions::DNI($inputs);
        
        return $inputs;
    }
}