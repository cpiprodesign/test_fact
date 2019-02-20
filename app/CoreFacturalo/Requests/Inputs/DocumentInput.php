<?php

namespace App\CoreFacturalo\Requests\Inputs;

use App\CoreFacturalo\Requests\Inputs\Common\ActionInput;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Requests\Inputs\Common\LegendInput;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Item;
use Illuminate\Support\Str;

class DocumentInput
{
    public static function set($inputs)
    {
        $document_type_id = $inputs['document_type_id'];
        $series = $inputs['series'];
        $number = $inputs['number'];

        $company = Company::active();
        $soap_type_id = $company->soap_type_id;
        $number = Functions::newNumber($soap_type_id, $document_type_id, $series, $number, Document::class);

        Functions::validateUniqueDocument($soap_type_id, $document_type_id, $series, $number, Document::class);

        $filename = Functions::filename($company, $document_type_id, $series, $number);
        $establishment = EstablishmentInput::set($inputs['establishment_id']);
        $customer = PersonInput::set($inputs['customer_id']);

        if(in_array($document_type_id, ['01', '03'])) {
            $array_partial = self::invoice($inputs);
            $invoice = $array_partial['invoice'];
            $note = null;
        } else {
            $array_partial = self::note($inputs);
            $note = $array_partial['note'];
            $invoice = null;
        }

        $inputs['type'] = $array_partial['type'];
        $inputs['group_id'] = $array_partial['group_id'];

        return [
            'type' => $inputs['type'],
            'group_id' => $inputs['group_id'],
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'establishment_id' => $inputs['establishment_id'],
            'establishment' => $establishment,
            'soap_type_id' => $soap_type_id,
            'state_type_id' => '01',
            'ubl_version' => '2.1',
            'filename' => $filename,
            'document_type_id' => $document_type_id,
            'series' => $series,
            'number' => $number,
            'date_of_issue' => $inputs['date_of_issue'],
            'time_of_issue' => $inputs['time_of_issue'],
            'customer_id' => $inputs['customer_id'],
            'customer' => $customer,
            'currency_type_id' => $inputs['currency_type_id'],
            'purchase_order' => $inputs['purchase_order'],
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
            'items' => self::items($inputs),
            'charges' => self::charges($inputs),
            'discounts' => self::discounts($inputs),
            'prepayments' => self::prepayments($inputs),
            'guides' => self::guides($inputs),
            'related' => self::related($inputs),
            'perception' => self::perception($inputs),
            'detraction' => self::detraction($inputs),
            'invoice' => $invoice,
            'note' => $note,
            'additional_information' => Functions::valueKeyInArray($inputs, 'additional_information'),
            'legends' => LegendInput::set($inputs),
            'actions' => ActionInput::set($inputs),
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
                    'attributes' => self::attributes($row),
                    'discounts' => self::discounts($row),
                    'charges' => self::charges($row),
                ];
            }
            return $items;
        }
        return null;
    }

    private static function attributes($inputs)
    {
        if(array_key_exists('attributes', $inputs)) {
            if($inputs['attributes']) {
                $attributes = [];
                foreach ($inputs['attributes'] as $row) {
                    $attribute_type_id = $row['attribute_type_id'];
                    $description = $row['description'];
                    $value = array_key_exists('value', $row)?$row['value']:null;
                    $start_date = array_key_exists('start_date', $row)?$row['start_date']:null;
                    $end_date = array_key_exists('start_date', $row)?$row['start_date']:null;
                    $duration = array_key_exists('duration', $row)?$row['duration']:null;

                    $attributes[] = [
                        'attribute_type_id' => $attribute_type_id,
                        'description' => $description,
                        'value' => $value,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'duration' => $duration,
                    ];
                }
                return $attributes;
            }
        }
        return null;
    }

    private static function charges($inputs)
    {
        if(array_key_exists('charges', $inputs)) {
            if($inputs['charges']) {
                $charges = [];
                foreach ($inputs['charges'] as $row) {
                    $charge_type_id = $row['charge_type_id'];
                    $description = $row['description'];
                    $factor = $row['factor'];
                    $amount = $row['amount'];
                    $base = $row['base'];

                    $charges[] = [
                        'charge_type_id' => $charge_type_id,
                        'description' => $description,
                        'factor' => $factor,
                        'amount' => $amount,
                        'base' => $base,
                    ];
                }
                return $charges;
            }
        }
        return null;
    }

    private static function discounts($inputs)
    {
        if(array_key_exists('discounts', $inputs)) {
            if($inputs['discounts']) {
                $discounts = [];
                foreach ($inputs['discounts'] as $row) {
                    $discount_type_id = $row['discount_type_id'];
                    $description = $row['description'];
                    $factor = $row['factor'];
                    $amount = $row['amount'];
                    $base = $row['base'];

                    $discounts[] = [
                        'discount_type_id' => $discount_type_id,
                        'description' => $description,
                        'factor' => $factor,
                        'amount' => $amount,
                        'base' => $base,
                    ];
                }
                return $discounts;
            }
        }
        return null;
    }

    private static function prepayments($inputs)
    {
        if(array_key_exists('prepayments', $inputs)) {
            if($inputs['prepayments']) {
                $prepayments = [];
                foreach ($inputs['prepayments'] as $row)
                {
                    $number = $row['number'];
                    $document_type_id = $row['document_type_id'];
                    $amount = $row['amount'];

                    $prepayments[] = [
                        'number' => $number,
                        'document_type_id' => $document_type_id,
                        'amount' => $amount
                    ];
                }
                return $prepayments;
            }
        }
        return null;
    }

    private static function guides($inputs)
    {
        if(array_key_exists('guides', $inputs)) {
            if($inputs['guides']) {
                $guides = [];
                foreach ($inputs['guides'] as $row) {
                    $number = $row['number'];
                    $document_type_id = $row['document_type_id'];

                    $guides[] = [
                        'number' => $number,
                        'document_type_id' => $document_type_id,
                    ];
                }
                return $guides;
            }
        }
        return null;
    }

    private static function related($inputs)
    {
        if(array_key_exists('related', $inputs)) {
            if($inputs['related']) {
                $related = [];
                foreach ($inputs['related'] as $row) {
                    $number = $row['number'];
                    $document_type_id = $row['document_type_id'];
                    $amount = $row['amount'];

                    $related[] = [
                        'number' => $number,
                        'document_type_id' => $document_type_id,
                        'amount' => $amount
                    ];
                }
                return $related;
            }
        }
        return null;
    }

    private static function perception($inputs)
    {
        if(array_key_exists('perception', $inputs)) {
            if($inputs['perception']) {
                $perception = $inputs['perception'];
                $code = $perception['code'];
                $percentage = $perception['percentage'];
                $amount = $perception['amount'];
                $base = $perception['base'];

                return [
                    'code' => $code,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'base' => $base,
                ];
            }
        }
        return null;
    }

    private static function detraction($inputs)
    {
        if(array_key_exists('detraction', $inputs)) {
            if($inputs['detraction']) {
                $detraction = $inputs['detraction'];
                $code = $detraction['code'];
                $percentage = $detraction['percentage'];
                $amount = $detraction['amount'];
                $payment_method_id = $detraction['payment_method_id'];
                $bank_account = $detraction['bank_account'];

                return [
                    'code' => $code,
                    'percentage' => $percentage,
                    'amount' => $amount,
                    'payment_method_id' => $payment_method_id,
                    'bank_account' => $bank_account,
                ];
            }
        }
        return null;
    }

    private static function invoice($inputs)
    {
        $operation_type_id = $inputs['operation_type_id'];
        $date_of_due = $inputs['date_of_due'];

        return [
            'type' => 'invoice',
            'group_id' => ($inputs['document_type_id'] === '01')?'01':'02',
            'invoice' => [
                'operation_type_id' => $operation_type_id,
                'date_of_due' => $date_of_due,
            ]
        ];
    }

    private static function note($inputs)
    {
        $document_type_id = $inputs['document_type_id'];
        $note_credit_or_debit_type_id = $inputs['note_credit_or_debit_type_id'];
        $note_description = $inputs['note_description'];
        $affected_document_id = $inputs['affected_document_id'];

        $affected_document = Document::find($affected_document_id);

        $type = ($document_type_id === '07')?'credit':'debit';

        return [
            'type' => $type,
            'group_id' => $affected_document->group_id,
            'note' => [
                'note_type' => $type,
                'note_credit_type_id' => ($type === 'credit')?$note_credit_or_debit_type_id:null,
                'note_debit_type_id' => ($type === 'debit')?$note_credit_or_debit_type_id:null,
                'note_description' => $note_description,
                'affected_document_id' => $affected_document->id
            ]
        ];
    }
}