<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Person;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseItem;
use App\CoreFacturalo\Requests\Inputs\Common\LegendInput;
use App\Models\Tenant\Item;
use App\Http\Resources\Tenant\PurchaseCollection;
use App\Http\Resources\Tenant\PurchaseResource;
use App\Models\Tenant\Catalogs\AffectationIgvType;  
use App\Models\Tenant\Catalogs\DocumentType;  
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Company;
use App\Http\Requests\Tenant\PurchaseRequest;
use Illuminate\Support\Str;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;

class PurchaseController extends Controller
{
    
    public function index()
    {
        return view('tenant.purchases.index');
    }

    public function create()
    {
        return view('tenant.purchases.form');
    }

    public function edit(Purchase $purchase)
    {
        return view('tenant.purchases.edit', compact('purchase'));
    }

    public function columns()
    {
        return [
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Purchase::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new PurchaseCollection($records->paginate(env('ITEMS_PER_PAGE', 20)));
    }

    public function totals(Request $request)
    {
        $totalPEN = DB::connection('tenant')
                        ->table('purchases')
                        ->select(DB::raw('COUNT(*) as quantity'), DB::raw('SUM(total) as total'))
                        //->where($request->column, 'like', "%{$request->value}%")
                        ->where('currency_type_id', 'PEN')
                        ->first();

        $totalUSD = DB::connection('tenant')
                    ->table('purchases')
                    ->select(DB::raw('COUNT(*) as quantity'), DB::raw('SUM(total) as total'))
                    ->where($request->column, 'like', "%{$request->value}%")
                    ->where('currency_type_id', 'USD')
                    ->first();

        $totalPEN = [
            'quantity' => $totalPEN->quantity,
            'total' => is_null($totalPEN->total) ? '0.00': $totalPEN->total 
        ];

        $totalUSD = [
            'quantity' => $totalUSD->quantity,
            'total' => is_null($totalUSD->total) ? '0.00' : $totalUSD->total 
        ];
        
        $data = [
            'totalPEN' => $totalPEN,
            'totalUSD' => $totalUSD
        ];

        return compact('data');
    }

    public function tables()
    {
        $suppliers = $this->table('suppliers');

        if(auth()->user()->admin)
        {
            $establishments = Establishment::all();
        }
        else
        {
            $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();
        }

        $currency_types = CurrencyType::whereActive()->get();
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();        
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();

        return compact('suppliers', 'establishment','currency_types', 'discount_types', 'charge_types', 'document_types_invoice','company', 'establishments');
    }

    public function tables2(Purchase $purchase)
    {
        $suppliers = Person::whereType('suppliers')->get()->transform(function ($row) {
            return [
                'id' => $row->id,
                'description' => $row->number . ' - ' . $row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
                'identity_document_type_code' => $row->identity_document_type->code
            ];
        });
        $establishments = Establishment::all();
        $currency_types = CurrencyType::whereActive()->get();
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();        
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();

        return compact('purchase', 'suppliers', 'establishment','currency_types', 'discount_types', 'charge_types', 'document_types_invoice','company', 'establishments');
    }

    public function item_tables()
    {
        $items = $this->table('items');
        $categories = []; 
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get(); 
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                        'discount_types', 'charge_types', 'attribute_types');
    }

    public function item_tables2(Purchase $purchase)
    {
        $purchase_items = PurchaseItem::where('purchase_id', $purchase->id)->get();

        $items = array();

        foreach ($purchase_items as $purchase_item) {
            $row = Item::whereId($purchase_item->item_id)->first();

            $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;

            $items[] = [
                'id' => $row->id,
                'full_description' => $full_description,
                'item_id' => $row->id,
                'unit_value' => $purchase_item->unit_value,
                'unit_price' => $purchase_item->unit_price,
                'quantity' => $purchase_item->quantity,
                'total_discount' => $purchase_item->total_discount,
                'total_charge' => $purchase_item->total_charge,
                'total' => $purchase_item->total,
                'total_taxes' => $purchase_item->total_taxes,
                'total_value' => $purchase_item->total_value,
                'affectation_igv_type_id' => $purchase_item->affectation_igv_type_id,
                'price_type_id' => $purchase_item->price_type_id,
                'total_base_igv' => $purchase_item->total_base_igv,
                'percentage_igv' => $purchase_item->percentage_igv,
                'total_igv' => $purchase_item->total_igv,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => $row->sale_unit_price,
                'purchase_unit_price' => $row->purchase_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'included_igv' => $row->included_igv,
                'item' => $row,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id
            ];
        }

        $categories = []; 
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get(); 
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();

        return compact('purchase', 'items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                        'discount_types', 'charge_types', 'attribute_types');
    }

    public function record($id)
    {
        $record = new PurchaseResource(Purchase::findOrFail($id));

        return $record;
    }

    public function store(PurchaseRequest $request)
    {   
        $data = self::convert($request);

        $purchase = DB::connection('tenant')->transaction(function () use ($data) {
            $doc = Purchase::create($data);

            foreach ($data['items'] as $row)
            {
                $doc->items()->create($row);

                $update = $doc->establishment_item()->firstOrNew(['item_id' => $row['item_id']]);
                $update->quantity += $row['quantity'];
                $update->save();
            }

            return $doc;
        });       

        return [
            'success' => true,
            'data' => [
                'id' => $purchase->id,
            ],
        ];        
    }

    public function update(PurchaseRequest $request, $purchase_id)
    {
        $inputs = $request;
        

        $array = [$inputs, $purchase_id];

        $purchase = DB::connection('tenant')->transaction(function () use ($array){

            $inputs = $array[0];
            $purchase_id = $array[1];
            
            $purchase = Purchase::find($purchase_id);
            $purchase_items = PurchaseItem::where('purchase_id', $purchase_id)->get();

            \App\Models\Tenant\Kardex::where('purchase_id', $purchase_id)->delete();
            \App\Models\Tenant\InventoryKardex::where('inventory_kardexable_id', $purchase_id)
                                                    ->where('inventory_kardexable_type', 'App\Models\Tenant\Purchase')
                                                    ->delete();

            foreach($purchase_items as $purchase_item)
            {   
                $item_warehouse = \App\Models\Tenant\ItemWarehouse::where('warehouse_id', $purchase->establishment_id)
                ->where('item_id', $purchase_item->item_id)->first();
                $item_warehouse->stock -= $purchase_item->quantity;
                $item_warehouse->save();
            }
            
            //eliminar
            PurchaseItem::where('purchase_id', $purchase_id)->delete();
            Purchase::where('id', $purchase_id)->delete();

            $data = self::convert($inputs);

            $doc = Purchase::create($data);

            foreach ($data['items'] as $row)
            {
                $doc->items()->create($row);

                $update = $doc->establishment_item()->firstOrNew(['item_id' => $row['item_id']]);
                $update->quantity += $row['quantity'];
                $update->save();
            }

            return $doc;           
        });

        return [
            'success' => true,
            'data' => [
                'id' => $purchase->id,
            ],
        ];        
    }

    public static function convert($inputs)
    {
        $company = Company::active();
        $values = [
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'supplier' => PersonInput::set($inputs['supplier_id']),
            'soap_type_id' => $company->soap_type_id,
            'group_id' => ($inputs->document_type_id === '01') ? '01':'02',
            'state_type_id' => '01'
        ]; 

        $inputs->merge($values);

        return $inputs->all();
    }

    public function table($table)
    {
        switch ($table) {
            case 'suppliers':

                $suppliers = Person::whereType('suppliers')->orderBy('name')->get()->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'description' => $row->number.' - '.$row->name,
                        'name' => $row->name,
                        'number' => $row->number,
                        'identity_document_type_id' => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code
                    ];
                });
                return $suppliers;

                break;
            
            case 'items':

                $items = Item::orderBy('description')->get()->transform(function($row) {
                    $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;
                    return [
                        'id' => $row->id,
                        'full_description' => $full_description,
                        'description' => $row->description,
                        'currency_type_id' => $row->currency_type_id,
                        'currency_type_symbol' => $row->currency_type->symbol,
                        'sale_unit_price' => $row->sale_unit_price,
                        'purchase_unit_price' => $row->purchase_unit_price,
                        'unit_type_id' => $row->unit_type_id,
                        'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                        'included_igv' => $row->included_igv,
                        'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id
                    ];
                });
                return $items;

                break;
            default:

                return [];

                break;
        } 
    }
}
