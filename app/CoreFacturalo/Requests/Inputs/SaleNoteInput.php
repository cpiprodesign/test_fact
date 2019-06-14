<?php

namespace App\CoreFacturalo\Requests\Inputs;

use App\CoreFacturalo\Requests\Inputs\Common\ActionInput;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Requests\Inputs\Common\LegendInput;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\Models\Tenant\Company;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Item;
use Illuminate\Support\Str;

class SaleNoteInput
{
    public static function set($inputs)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series'];
        $number = $inputs['number'];

        $sale_note_id = $inputs['sale_note_id'];

        $company = Company::active();
        
        $number = Functions::newNumber2($document_type_id, $series, $number, SaleNote::class);
        Functions::validateUniqueDocument2($document_type_id, $series, $number, SaleNote::class);
        $created_at = date("Y-m-d H:i:s");
             

        $filename = Functions::filename($company, $document_type_id, $series, $number);
        $establishment = EstablishmentInput::set($inputs['establishment_id']);
        $customer = PersonInput::set($inputs['customer_id']);
        
        //$array_partial = self::invoice($inputs);
        //$invoice = $array_partial['invoice'];
        //$note = null;       

        $inputs['type'] = 'sale-note';
        //$inputs['group_id'] = $array_partial['group_id'];

        return [
            'type' => $inputs['type'],
            //'group_id' => $inputs['group_id'],
            'user_id' => auth()->id(),
            //'external_id' => Str::uuid()->toString(),
            'establishment_id' => $inputs['establishment_id'],
            'establishment' => $establishment,
            //'state_type_id' => '01',
            //'ubl_version' => '2.1',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $inputs['date_of_issue'],
            'time_of_issue' => $inputs['time_of_issue'],
            'customer_id' => $inputs['customer_id'],
            'customer' => $customer,
            'currency_type_id' => $inputs['currency_type_id'],
            //'purchase_order' => $inputs['purchase_order'],
            'exchange_rate_sale' => $inputs['exchange_rate_sale'],
            'total_prepayment' => Functions::valueKeyInArray($inputs, 'total_prepayment', 0),
            'total_discount' => Functions::valueKeyInArray($inputs, 'total_discount', 0),
            'total_charge' => Functions::valueKeyInArray($inputs, 'total_charge', 0),
            'total_exportation' => Functions::valueKeyInArray($inputs, 'total_exportation', 0),
            'total_free' => Functions::valueKeyInArray($inputs, 'total_free', 0),
            'total_taxed' => $inputs['total_taxed'],
            'total_unaffected' => $inputs['total_unaffected'],
            'total_exonerated' => $inputs['total_exonerated'],
            'total_igv' => $inputs['total_igv'],
            'total_base_isc' => Functions::valueKeyInArray($inputs, 'total_base_isc', 0),
            'total_isc' => Functions::valueKeyInArray($inputs, 'total_isc', 0),
            'total_base_other_taxes' => Functions::valueKeyInArray($inputs, 'total_base_other_taxes', 0),
            'total_other_taxes' => Functions::valueKeyInArray($inputs, 'total_other_taxes', 0),
            'total_taxes' => $inputs['total_taxes'],
            'total_value' => $inputs['total_value'],
            'total' => $inputs['total'],
            'total_paid' => 0,
            'items' => self::items($inputs),
            //'charges' => self::charges($inputs),
            // 'discounts' => self::discounts($inputs),
            // 'prepayments' => self::prepayments($inputs),
            // 'guides' => self::guides($inputs),
            // 'related' => self::related($inputs),
            // 'perception' => self::perception($inputs),
            // 'detraction' => self::detraction($inputs),
            //'invoice' => $invoice,
            //'note' => $note,
            'additional_information' => Functions::valueKeyInArray($inputs, 'additional_information'),
            'legends' => LegendInput::set($inputs),
            'actions' => ActionInput::set($inputs),
            'created_at' => $created_at
        ];
    }

    private static function items($inputs)
    {
        if(array_key_exists('items', $inputs)) {
            $items = [];
            foreach ($inputs['items'] as $row) {
                $item = Item::find($row['item_id']);
                $items[] = [
                    'item_id' => $item->id,
                    'item' => [
                        'description' => $item->description,
                        'item_type_id' => $item->item_type_id,
                        'internal_id' => $item->internal_id,
                        'item_code' => $item->item_code,
                        'item_code_gs1' => $item->item_code_gs1,
                        'unit_type_id' => $item->unit_type_id,
                    ],
                    'quantity' => $row['quantity'],
                    'unit_value' => $row['unit_value'],
                    'price_type_id' => $row['price_type_id'],
                    'unit_price' => $row['unit_price'],
                    'affectation_igv_type_id' => $row['affectation_igv_type_id'],
                    'total_base_igv' => $row['total_base_igv'],
                    'percentage_igv' => $row['percentage_igv'],
                    'total_igv' => $row['total_igv'],
                    'system_isc_type_id' => $row['system_isc_type_id'],
                    'total_base_isc' => Functions::valueKeyInArray($row, 'total_base_isc', 0),
                    'percentage_isc' => Functions::valueKeyInArray($row, 'percentage_isc', 0),
                    'total_isc' => Functions::valueKeyInArray($row, 'total_isc', 0),
                    'total_base_other_taxes' => Functions::valueKeyInArray($row, 'total_base_other_taxes', 0),
                    'percentage_other_taxes' => Functions::valueKeyInArray($row, 'percentage_other_taxes', 0),
                    'total_other_taxes' => Functions::valueKeyInArray($row, 'total_other_taxes', 0),
                    'total_taxes' => $row['total_taxes'],
                    'total_value' => $row['total_value'],
                    'total_charge' => Functions::valueKeyInArray($row, 'total_charge', 0),
                    'total_discount' => Functions::valueKeyInArray($row, 'total_discount', 0),
                    'total' => $row['total'],
                ];
            }
            return $items;
        }
        return null;
    }

    // private static function invoice($inputs)
    // {
    //     $operation_type_id = $inputs['operation_type_id'];
    //     $date_of_due = $inputs['date_of_due'];

    //     return [
    //         'type' => 'quotation',
    //         'group_id' => ($inputs['document_type_id'] === '01')?'01':'02',
    //         'invoice' => [
    //             'operation_type_id' => $operation_type_id,
    //             'date_of_due' => $date_of_due,
    //         ]
    //     ];
    // }
}