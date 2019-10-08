<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Facturalo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentEmailRequest;
use App\Http\Requests\Tenant\SaleNoteRequest;
use App\Http\Resources\Tenant\SaleNoteCollection;
use App\Http\Resources\Tenant\SaleNoteResource;
use App\Mail\Tenant\DocumentEmail;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\OperationType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Pos;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Catalogs\PaymentMethod;
use App\Models\Tenant\Account;
use App\Models\Tenant\Kardex;
use App\Models\Tenant\Person;
use App\Models\Tenant\Series;
use App\Models\Tenant\Warehouse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Nexmo\Account\Price;

class SaleNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('input.request:saleNote,web', ['only' => ['store', 'update']]);
    }

    public function index()
    {
        return view('tenant.sale_notes.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
        $records = SaleNote::where($request->column, 'like', "%{$request->value}%")
                            ->latest();

        return new SaleNoteCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function totals(Request $request)
    {
        $total = DB::connection('tenant')
                        ->table('sale_notes')
                        ->select(DB::raw('SUM(total) as total'), DB::raw('SUM(total_paid) as total_paid'), DB::raw('SUM(total) - SUM(total_paid) as total_to_pay'))
                        ->where($request->column, 'like', "%{$request->value}%")
                        ->where('currency_type_id', 'PEN')
                        ->first();
        
        $data = [
            'total' => $total
        ];

        return compact('data');
    }

    public function create()
    {
        return view('tenant.sale_notes.form');
    }

    public function edit($id)
    {
        $saleNote = SaleNote::with('payment')->find($id);

        if($saleNote == null)
        {
            return redirect('/sale-notes');
        }

        return view('tenant.sale_notes.edit_salenote', compact('saleNote'));
    }

    public function tables()
    {
        $customers = $this->table('customers');
        if(auth()->user()->admin)
        {
            $establishments = Establishment::all();
        }
        else
        {
            $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();
        }

        $warehouse_id = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first()->id;    
        $warehouses = Warehouse::get();
        $series = Series::all();
        $document_types_invoice = DocumentType::whereIn('id', ['100'])->get();
        $currency_types = CurrencyType::whereActive()->get();
        $company = Company::active();
        $payment_methods = PaymentMethod::whereActive()->get();
        $accounts = Account::all();
        $decimal = Configuration::first()->decimal;

        return compact('customers', 'establishments', 'warehouse_id', 'warehouses', 'series', 'document_types_invoice', 'currency_types', 'company', 'document_type_03_filter', 'payment_methods', 'accounts', 'decimal');
    }

    public function item_tables()
    {
        $items = $this->table('items');
        $categories = [];//Category::cascade();
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                       'operation_types', 'discount_types', 'charge_types', 'attribute_types');
    }

    public function table($table)
    {
        if ($table === 'customers') {
            $customers = Person::whereType('customers')->orderBy('name')->get()->transform(function($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number.' - '.$row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code
                ];
            });
            return $customers;
        }
        if ($table === 'items') {
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
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id
                ];
            });
            return $items;
        }

        return [];
    }

    public function record($id)
    {
        $record = new SaleNoteResource(SaleNote::findOrFail($id));

        return $record;
    }

    public function store(SaleNoteRequest $request)
    {
        $pos = Pos::active();

        if($pos == null)
        {
            return [
                'success' => false,
                'message' => "!Necesita aperturar una caja!"
            ];
        }
        else
        {
            $array = [$request, $pos];

            $fact = DB::connection('tenant')->transaction(function () use ($array) {
                
                $request = $array[0];
                $pos = $array[1];
                
                $facturalo = new Facturalo();
                $facturalo->save($request->all());
                $facturalo->createPdf2();

                $document = $facturalo->getDocument();

                $pos_sales = new \App\Models\Tenant\PosSales();
                $pos_sales->table_name = 'sale_notes';
                $pos_sales->document_id = $document->id;
                $pos_sales->pos_id = $pos;
                
                $pos_sales->save();
                
                return $facturalo;
            });
    
            $document = $fact->getDocument();
    
            return [
                'success' => true,
                'data' => [
                    'id' => $document->id,
                ],
            ];
        }
    }

    public function item_tables2($saleNoteId)
    {
        $sale_note_items = SaleNoteItem::where('sale_note_id', $saleNoteId)->get();

        $items = array();

        foreach ($sale_note_items as $sale_note_item) {
            $row = Item::with('sale_affectation_igv_type')->whereId($sale_note_item->item_id)->first();

            $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;

            $items[] = [
                'id' => $row->id,
                'full_description' => $full_description,
                'item_id' => $row->id,
                'unit_value' => $sale_note_item->unit_value,
                'unit_price' => $sale_note_item->unit_price,
                'quantity' => $sale_note_item->quantity,
                'total_discount' => $sale_note_item->total_discount,
                'total_charge' => $sale_note_item->total_charge,
                'total' => $sale_note_item->total,
                'total_taxes' => $sale_note_item->total_taxes,
                'total_value' => $sale_note_item->total_value,
                'affectation_igv_type_id' => $sale_note_item->affectation_igv_type_id,
                'price_type_id' => $sale_note_item->price_type_id,
                'total_base_igv' => $sale_note_item->total_base_igv,
                'percentage_igv' => $sale_note_item->percentage_igv,
                'total_igv' => $sale_note_item->total_igv,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => $row->sale_unit_price,
                'purchase_unit_price' => $row->sale_note_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'included_igv' => $row->included_igv,
                'item' => $row,
                'purchase_affectation_igv_type_id' => $row->sale_note_affectation_igv_type_id,
                'affectation_igv_type' =>$row->sale_affectation_igv_type,
                'system_isc_type_id' => $row->system_isc_type_id,
            ];
        }

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

    public function update(SaleNoteRequest $request, $sale_note_id)
    {  
        $pos = Pos::active();

        if($pos == null)
        {
            return [
                'success' => false,
                'message' => "!Necesita aperturar una caja!"
            ];
        }
        $inputs = $request;
        $array = [$inputs, (int)$sale_note_id, $pos];

        $sale_note = DB::connection('tenant')->transaction(function () use ($array){

            $inputs = $array[0];
            $sale_note_id = $array[1];
            $pos = $array[2];

            $sale_note = SaleNote::find($sale_note_id);
            $sale_note_items = SaleNoteItem::where('sale_note_id', $sale_note_id)->get();

            \App\Models\Tenant\Kardex::where('sale_note_id', $sale_note_id)->delete();
            \App\Models\Tenant\InventoryKardex::where('inventory_kardexable_id', $sale_note_id)
                                                    ->where('inventory_kardexable_type', 'App\Models\Tenant\SaleNote')
                                                    ->delete();

            foreach($sale_note_items as $sale_note_item)
            {   
                $item_warehouse = \App\Models\Tenant\ItemWarehouse::where('warehouse_id', $sale_note->warehouse_id)
                ->where('item_id', $sale_note_item->item_id)->first();
                $item_warehouse->stock += $sale_note_item->quantity;
                $item_warehouse->save();
            }
            
            DB::statement("UPDATE pos INNER JOIN payments pay ON pay.pos_id = pos.id
                                SET pos.close_amount = pos.close_amount - pay.total
                                WHERE pay.sale_note_id = $sale_note_id"); 

            DB::statement("UPDATE accounts acc
                        INNER JOIN payments pay ON pay.account_id = acc.id
                        SET acc.current_balance = acc.current_balance - pay.total
                        WHERE pay.sale_note_id = $sale_note_id");

            //eliminar
            SaleNoteItem::where('sale_note_id', $sale_note_id)->delete();
            SaleNote::where('id', $sale_note_id)->delete();

            $inputs = $inputs->all();
    
            $facturalo = new Facturalo();
            $facturalo->save($inputs);
            $facturalo->createPdf2();

            $document = $facturalo->getDocument();

            $pos_sales = new \App\Models\Tenant\PosSales();
            $pos_sales->table_name = 'sale_notes';
            $pos_sales->document_id = $document->id;
            $pos_sales->pos_id = $pos;
            
            $pos_sales->save();
            
           return $document = $facturalo->getDocument();
        });

        return [
            'success' => true,
            'data' => [
                'id' => $sale_note->id,
            ],
        ];        
    }

    public function destroy($id)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($id){

            $sale_note = SaleNote::find($id);
            $sale_note_items = SaleNoteItem::where('sale_note_id', $id)->get();

            foreach($sale_note_items as $sale_note_item)
            {
                $item_warehouse = \App\Models\Tenant\ItemWarehouse::where('warehouse_id', $sale_note->establishment_id)
                ->where('item_id', $sale_note_item->item_id)->first();
                $item_warehouse->stock += $sale_note_item->quantity;
                $item_warehouse->save();
            }

            Kardex::where('type', 'sale-note')->where('sale_note_id', $id)->delete();

            \App\Models\Tenant\InventoryKardex::where('inventory_kardexable_id', $id)
                                                    ->where('inventory_kardexable_type', 'App\Models\Tenant\SaleNote')
                                                    ->delete();

            $payments = \App\Models\Tenant\Payment::where('sale_note_id', $id)->get();

            foreach($payments as $payment)
            {   
                $pos = Pos::find($payment->pos_id);

                $pos->close_amount -= $payment->total;
                $pos->save();

                $account = Account::find($payment->account_id);
                $account->current_balance -= $payment->total;
                $account->save();
            }

            \App\Models\Tenant\Payment::where('sale_note_id', $id)->delete();
            \App\Models\Tenant\PosSales::where('document_id', $id)->where('table_name', 'sale_notes')->delete();

            SaleNote::where('id', $id)->delete();
            SaleNoteItem::where('sale_note_id', $id)->delete();

            return true;
        });

        return [
            'success' => true,
            'message' => 'Nota de venta eliminada con éxito'
        ];
    }

  
}