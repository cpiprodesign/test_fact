<?php

namespace App\CoreFacturalo\Requests\Web\Validation;

class SaleNoteValidation
{
    public static function validation($inputs) {
        
        if($inputs['sale_note_id'] == null){

            $series = Functions::findSeries($inputs);
            $inputs['series'] = $series->number;
           
        }else{
            $inputs['series'] = $inputs['series_id'];
        }
        unset($inputs['series_id']);
        
        Functions::DNI($inputs);
        
        return $inputs;
    }
}