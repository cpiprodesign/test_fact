<?php

namespace App\Helpers;

class Functions
{
    public static function formaterDecimal($value)
    {
        $split = explode(".", $value);
        
        if(count($split) > 1)
        {
            if($split[1] == '0000')
            {
                $value = (int)$value;
            }
        }

        return $value;
    }
}