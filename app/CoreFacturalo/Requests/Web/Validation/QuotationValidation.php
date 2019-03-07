<?php

namespace App\CoreFacturalo\Requests\Web\Validation;

class QuotationValidation
{
    public static function validation($inputs) {
        $series = Functions::findSeries($inputs);
        $inputs['series'] = $series->number;
        unset($inputs['series_id']);
        
        Functions::DNI($inputs);
        
        return $inputs;
    }
}