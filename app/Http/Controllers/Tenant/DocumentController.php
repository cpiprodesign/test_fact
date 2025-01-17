<?php

namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentEmailRequest;
use App\Http\Requests\Tenant\DocumentRequest;
use App\Http\Requests\Tenant\DocumentConfigurationRequest;
use App\Http\Requests\Tenant\DocumentVoidedRequest;
use App\Http\Resources\Tenant\DocumentCollection;
use App\Http\Resources\Tenant\DocumentResource;
use App\Http\Resources\Tenant\DocumentConfigurationResource;
use App\Mail\Tenant\DocumentEmail;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\NoteCreditType;
use App\Models\Tenant\Catalogs\NoteDebitType;
use App\Models\Tenant\Catalogs\OperationType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\PaymentMethod;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Account;
use App\Models\Tenant\PriceList;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\QuotationItem;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentConfiguration;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Person;
use App\Models\Tenant\Series;
use App\Models\Tenant\Pos;
use App\Models\Tenant\PosSales;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Warehouse;
use Nexmo\Account\Price;
use App\Models\Tenant\DocumentItem;

class DocumentController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('input.request:document,web', ['only' => ['store','update']]);
    }

    public function index()
    {
        return view('tenant.documents.index');
    }

    public function view(Document $document)
    {
        $payments = Payment::where('document_id', $document->id)->get();

        return view('tenant.documents.view', compact('document', 'payments'));
    }

    public function configuration()
    {
        return view('tenant.documents.configuration');
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
        $records = Document::where($request->column, 'like', "%{$request->value}%")
            ->whereIn('document_type_id', ['01', '03'])
            ->orderBy('date_of_issue', 'desc')
            ->orderBy('series', 'desc')
            ->orderBy('number', 'desc');

        return new DocumentCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function totals(Request $request)
    {
        $total = DB::connection('tenant')
                        ->table('documents')
                        ->select(DB::raw('SUM(total) as total'), DB::raw('SUM(total_paid) as total_paid'), DB::raw('SUM(total) - SUM(total_paid) as total_to_pay'))
                        ->where($request->column, 'like', "%{$request->value}%")
                        ->whereIn('document_type_id', ['01', '03'])
                        ->whereIn('state_type_id', ['01', '03', '05', '07'])
                        ->where('currency_type_id', 'PEN')
                        ->first();

        $total01 = DB::connection('tenant')
            ->table('documents')
            ->select(DB::raw('COUNT(*) as quantity'), DB::raw('SUM(total) as total'))
            ->where($request->column, 'like', "%{$request->value}%")
            ->whereIn('document_type_id', ['01'])
            ->where('currency_type_id', 'PEN')
            ->first();
        
        $total03 = DB::connection('tenant')
            ->table('documents')
            ->select(DB::raw('COUNT(*) as quantity'), DB::raw('SUM(total) as total'))
            ->where($request->column, 'like', "%{$request->value}%")
            ->whereIn('document_type_id', ['03'])
            ->where('currency_type_id', 'PEN')
            ->first();

        $total_state_types = DB::connection('tenant')
            ->table('documents AS doc')
            ->join('state_types AS stp', 'stp.id', 'doc.state_type_id')
            ->select(DB::raw('COUNT(*) AS quantity'), 'stp.description')
            ->where($request->column, 'like', "%{$request->value}%")
            ->whereIn('doc.document_type_id', ['01', '03'])
            ->groupBy('stp.description')
            ->get();
        
        $data = [
            'total' => $total, 
            'total01' => $total01, 
            'total03' => $total03,
            'total_state_types' => $total_state_types
        ];

        return compact('data');
    }

    public function create()
    {
        //$user = auth()->user();
        //$pos = \App\Models\Tenant\Pos::active();
        
        return view('tenant.documents.form');
    }

    public function create2($quotation_id)
    {
        $user = auth()->user();
        $pos = \App\Models\Tenant\Pos::active();
        return view('tenant.documents.form2', compact('quotation_id', 'user', 'pos'));
    }

    public function edit($documentId) 
    {
        $document = Document::with('payment')->find($documentId);

        if(is_null($document) || $document->state_type_id != 1){
            return redirect('/documents');
        }

        return view('tenant.documents.edit',compact('document'));
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
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        $document_types_invoice2 = DocumentType::whereIn('id', ['01', '03', '100'])->get();
        $document_types_note = DocumentType::whereIn('id', ['07', '08'])->get();
        $note_credit_types = NoteCreditType::whereActive()->orderByDescription()->get();
        $note_debit_types = NoteDebitType::whereActive()->orderByDescription()->get();
        $currency_types = CurrencyType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $payment_methods = PaymentMethod::whereActive()->get();
        $accounts = Account::all();
        $price_list = PriceList::all();
        $company = Company::active();
        $document_types_guide = DocumentType::whereIn('id', ['09', '31'])->get();
        $document_type_03_filter = env('DOCUMENT_TYPE_03_FILTER', true);
        $decimal = Configuration::first()->decimal;

        return compact('customers', 'establishments', 'warehouse_id', 'warehouses', 'series', 'document_types_invoice', 'document_types_invoice2', 'document_types_note',
            'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
            'discount_types', 'charge_types', 'payment_methods', 'accounts', 'company', 'document_types_guide', 'document_type_03_filter', 'decimal', 'price_list');
    }

    public function tables2($quotation_id = false)
    {
        if(strlen(stristr($quotation_id, 'e')) == 0)
        {
            $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        }
        else 
        {
            $document_types_invoice = DocumentType::whereIn('id', ['02'])->get();
        }

        $quotation_id = (int)$quotation_id;

        $quotation = Quotation::whereId($quotation_id)->get();
        
        $customers = Person::whereType('customers')->get()->transform(function ($row) {
            return [
                'id' => $row->id,
                'description' => $row->number . ' - ' . $row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
                'identity_document_type_code' => $row->identity_document_type->code
            ];
        });

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
        
        $document_types_note = DocumentType::whereIn('id', ['07', '08'])->get();
        $note_credit_types = NoteCreditType::whereActive()->orderByDescription()->get();
        $note_debit_types = NoteDebitType::whereActive()->orderByDescription()->get();
        $currency_types = CurrencyType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();
        $document_types_guide = DocumentType::whereIn('id', ['09', '31'])->get();
        $document_type_03_filter = env('DOCUMENT_TYPE_03_FILTER', true);
        $decimal = Configuration::first()->decimal;

        return compact('quotation', 'customers', 'establishments', 'warehouse_id', 'warehouses', 'series', 'document_types_invoice', 'document_types_note',
            'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
            'discount_types', 'charge_types', 'company', 'document_types_guide', 'document_type_03_filter', 'decimal');
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

    public function item_tables2($quotation_id)
    {
        $quotation = Quotation::where('id', $quotation_id)->first();
        $quotation_items = QuotationItem::where('quotation_id', $quotation_id)->get();

        $items = array();

        foreach ($quotation_items as $quotation_item) {
            $row = Item::whereId($quotation_item->item_id)->first();

            $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;

            $items[] = [
                'id' => $row->id,
                'full_description' => $full_description,
                'item_id' => $row->id,
                'unit_value' => $quotation_item->unit_value,
                'affectation_igv_type_id' => $quotation_item->affectation_igv_type_id,
                'price_type_id' => $quotation_item->price_type_id,
                'total_base_igv' => $quotation_item->total_base_igv,
                'percentage_igv' => $quotation_item->percentage_igv,
                'total_igv' => $quotation_item->total_igv,
                'system_isc_type_id' => $row->system_isc_type_id,
                'total_taxes' => $quotation_item->total_taxes,
                'quantity' => $quotation_item->quantity,
                'unit_price' => $quotation_item->unit_price,
                'total_value' => $quotation_item->total_value,
                'total' => $quotation_item->total,
                'item' => $row,
                'item.description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => $this->formatNumber($row->sale_unit_price),
                'has_plastic_bag_taxes' => $row->icbper,
                'purchase_unit_price' => $this->formatNumber($row->purchase_unit_price),
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                'amount_plastic_bag_taxes' => $row->amount_plastic_bag_taxes,
            ];
        }

        $categories = [];//Category::cascade();
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
            'operation_types', 'discount_types', 'charge_types', 'attribute_types', 'quotation');
    }

    public function table($table)
    {
        if ($table === 'customers') {
            $customers = Person::whereType('customers')->orderBy('name')->get()->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number . ' - ' . $row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code
                ];
            });
            return $customers;
        }
        if ($table === 'items') {
            $items = Item::orderBy('description')->get()->transform(function ($row) {
                $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;
                return [
                    'id' => $row->id,
                    'full_description' => $full_description,
                    'description' => $row->description,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => $this->formatNumber($row->sale_unit_price),
                    'purchase_unit_price' => $this->formatNumber($row->purchase_unit_price),
                    'included_igv' => $row->included_igv,
                    'amount_plastic_bag_taxes' => $row->amount_plastic_bag_taxes,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                    'has_plastic_bag_taxes' => $row->icbper,
                    'item_price_list' => $row->item_price_list
                ];
            });
            return $items;
        }

        return [];
    }

    public function record($id)
    {
        $record = new DocumentResource(Document::findOrFail($id));

        return $record;
    }

    public function configuration_record() 
    {
        $document_configuration = DocumentConfiguration::first();
        $record = new DocumentConfigurationResource($document_configuration);

        return $record;
    }

    public function store(DocumentRequest $request)
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
                $facturalo->createXmlUnsigned();
                $facturalo->signXmlUnsigned();
                $facturalo->updateHash();
                $facturalo->updateQr();
                $facturalo->createPdf();
    
                if ($request->input('quotation_id')) {
                    Quotation::where('id', $request->input('quotation_id'))
                        ->update(['state_type_id' => '05']);
                }

                $document = $facturalo->getDocument();

                $pos_sales = new PosSales();
                $pos_sales->table_name = 'documents';
                $pos_sales->document_id = $document->id;
                $pos_sales->pos_id = $pos;
                
                $pos_sales->save();

                return $facturalo;
            });
    
            $fact->senderXmlSignedBill();
            $document = $fact->getDocument();
            $response = $fact->getResponse();

            
            $document_sunat = Document::find($document->id);
            $document_sunat->sunat_information = $response;
            $document_sunat->save();
    
            return [
                'success' => true,
                'data' => [
                    'id' => $document->id
                ],
            ];
        }
        
    }

    public function configuration_store(DocumentConfigurationRequest $request)
    {
        $id = $request->input('id');
        $document_configuration = DocumentConfiguration::firstOrNew(['id' => $id]);
        $document_configuration->fill($request->all());
        $document_configuration->save();
        
        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];

    }

    public function email(DocumentEmailRequest $request)
    {
        $company = Company::active();
        $document = Document::find($request->input('id'));
        $customer_email = $request->input('customer_email');

        Mail::to($customer_email)->send(new DocumentEmail($company, $document));

        return [
            'success' => true
        ];
    }

    public function send($document_id)
    {
        $document = Document::find($document_id);

        $fact = DB::connection('tenant')->transaction(function () use ($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->loadXmlSigned();
            $facturalo->onlySenderXmlSignedBill();

            return $facturalo;
        });

        $response = $fact->getResponse();

        $document_sunat = Document::find($document->id);
        $document_sunat->sunat_information = $response;
        $document_sunat->save();

        return [
            'success' => true,
            'code' => $response['code'],
            'message' => 'Código: '.$response['code'].'; Descripción: '.$response['description'],
        ];
    }

    public function consultCdr($document_id)
    {
        $document = Document::find($document_id);

        $fact = DB::connection('tenant')->transaction(function () use ($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->consultCdr();
        });

        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }

    public function formatNumber($value)
    {
        $decimal = Configuration::first()->decimal;
        return number_format($value, $decimal, ".", "");
    }

    public function item_tables_by_document($documentId)
    {
        $document_items = DocumentItem::where('document_id', $documentId)->get();

        $items = array();

        foreach ($document_items as $document_item) {
            $row = Item::with('sale_affectation_igv_type')->whereId($document_item->item_id)->first();

            $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;

            $items[] = [
                'id' => $row->id,
                'full_description' => $full_description,
                'item_id' => $row->id,
                'unit_value' => $document_item->unit_value,
                'unit_price' => $document_item->unit_price,
                'quantity' => $document_item->quantity,
                'total_discount' => $document_item->total_discount,
                'total_charge' => $document_item->total_charge,
                'total' => $document_item->total,
                'total_taxes' => $document_item->total_taxes,
                'total_value' => $document_item->total_value,
                'affectation_igv_type_id' => $document_item->affectation_igv_type_id,
                'price_type_id' => $document_item->price_type_id,
                'total_base_igv' => $document_item->total_base_igv,
                'percentage_igv' => $document_item->percentage_igv,
                'total_igv' => $document_item->total_igv,
                'description' => $row->description,
                'currency_type_id' => $row->currency_type_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => $row->sale_unit_price,
                'purchase_unit_price' => $row->sale_note_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'included_igv' => $row->included_igv,
                'item' => $row,
                'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                'affectation_igv_type' =>$row->sale_affectation_igv_type,
                'system_isc_type_id' => $row->system_isc_type_id,
                'item_price_list' => $row->item_price_list
            ];
        }

        $categories = [];//Category::cascade();
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
            'operation_types', 'discount_types', 'charge_types', 'attribute_types', 'quotation');
    }

    public function update(DocumentRequest $request, $documentId)
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
            $array = [$request, $pos, (int)$documentId];

            $fact = DB::connection('tenant')->transaction(function () use ($array) {

                $request = $array[0];
                $pos = $array[1];
                $documentId = $array[2];

                $document = Document::find($documentId);
                $documentItems = DocumentItem::where('document_id', $documentId)->get();

                \App\Models\Tenant\Kardex::where('document_id', $documentId)->delete();
                \App\Models\Tenant\InventoryKardex::where('inventory_kardexable_id', $documentId)
                                                        ->where('inventory_kardexable_type', 'App\Models\Tenant\Document')
                                                        ->delete();

                foreach($documentItems as $documentItem)
                {   
                    $item_warehouse = \App\Models\Tenant\ItemWarehouse::where('warehouse_id', $document->warehouse_id)
                    ->where('item_id', $documentItem->item_id)->first();
                    $item_warehouse->stock += $documentItem->quantity;
                    $item_warehouse->save();
                }

                DB::statement("UPDATE pos INNER JOIN payments pay ON pay.pos_id = pos.id
                                SET pos.close_amount = pos.close_amount - pay.total
                                WHERE pay.document_id = $documentId"); 

                DB::statement("UPDATE accounts acc
                            INNER JOIN payments pay ON pay.account_id = acc.id
                            SET acc.current_balance = acc.current_balance - pay.total
                            WHERE pay.document_id = $documentId");

                \App\Models\Tenant\Payment::where('document_id', $documentId)->delete();
                \App\Models\Tenant\PosSales::where('document_id', $documentId)->where('table_name', 'documents')->delete();

                //delete
                DocumentItem::where('document_id', $documentId)->delete();
                Document::where('id', $documentId)->delete();
                // add again
                
                $facturalo = new Facturalo();
                $facturalo->save($request->all());
                $facturalo->createXmlUnsigned();
                $facturalo->signXmlUnsigned();
                $facturalo->updateHash();
                $facturalo->updateQr();
                $facturalo->createPdf();

                $document = $facturalo->getDocument();

                $pos_sales = new PosSales();
                $pos_sales->table_name = 'documents';
                $pos_sales->document_id = $document->id;
                $pos_sales->pos_id = $pos;
                
                $pos_sales->save();

                return $facturalo;
            });
    
            $fact->senderXmlSignedBill();
            $document = $fact->getDocument();
            $response = $fact->getResponse();
    
            return [
                'success' => true,
                'data' => [
                    'id' => $document->id,
                ],
            ];
        }
    }
}
