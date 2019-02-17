<?php

namespace App\CoreFacturalo\Requests\Api\Validation;

use App\Models\Tenant\Item;
use Exception;

class DispatchValidation
{
    public static function validation($inputs)
    {
        $inputs['establishment_id'] = Functions::establishment($inputs['establishment']);
        unset($inputs['establishment']);

        Functions::validateSeries($inputs);

        $inputs['customer_id'] = Functions::person($inputs['customer'], 'customers');
        unset($inputs['customer']);

        $inputs['items'] = self::items($inputs['items']);

        return $inputs;
    }

    private static function items($inputs)
    {
        $items = [];
        foreach ($inputs as $row)
        {
            $item = Item::where('internal_id', $row['internal_id'])->first();
            if(!$item) {
                throw new Exception("El código interno {$row['internal_id']} no fue encontrado.");
            }
            $items[] = [
                'item_id' => $item->id,
                'quantity' => $row['quantity']
            ];
        }

        return $items;
    }
}