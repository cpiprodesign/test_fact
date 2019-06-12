<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Facturalo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\DocumentEmailRequest;
use App\Http\Requests\Tenant\SaleNoteRequest;
use App\Http\Requests\Tenant\DocumentVoidedRequest;
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
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\Kardex;
use App\Models\Tenant\Person;
use App\Models\Tenant\Series;
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

    public function create()
    {
        return view('tenant.sale_notes.form');
    }

    public function edit($quotation_id)
    {
        return view('tenant.quotations.edit', compact('quotation_id'));
    }

    public function tables()
    {
        $customers = $this->table('customers');
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();// Establishment::all();
        $series = Series::all();
        $document_types_invoice = DocumentType::whereIn('id', ['100'])->get();
        $currency_types = CurrencyType::whereActive()->get();
        $company = Company::active();

        return compact('customers', 'establishments', 'series', 'document_types_invoice', 'currency_types', 'company', 'document_type_03_filter');
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
        $fact = DB::connection('tenant')->transaction(function () use ($request) {

            $inputs = $request->all();

            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createPdf2();
            
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

    public function update(QuotationRequest $request, $quotation_id)
    {
        $inputs = $request->all();       

        $array = [$inputs, $quotation_id];

        $fact = DB::connection('tenant')->transaction(function () use ($array){

            $inputs = $array[0];
            $quotation_id = $array[1];           
            
            $facturalo = new Facturalo();
            $this->document = $facturalo->updateQuotation($inputs, $quotation_id);
            $facturalo->createPdf2($this->document, 'quotation', 'a4');

            return $this->document;            
        });

        $document = $fact;

        return [
            'success' => true,
            'data' => [
                'id' => $document->id,
            ],
        ];        
    }

    public function email(DocumentEmailRequest $request)
    {
        $company = Company::active();
        $document = SaleNote::find($request->input('id'));
        $customer_email = $request->input('customer_email');

        Mail::to($customer_email)->send(new DocumentEmail($company, $document));

        return [
            'success' => true
        ];
    }

    public function destroy($id)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($id){

            $sale_note = SaleNote::find($id);
            $sale_note_item = SaleNoteItem::where('sale_note_id', $id)->get();

            foreach($sale_note_item as $item)
            {
                $update = $sale_note->establishment_item()->firstOrNew(['item_id' => $item->item_id]);
                $update->quantity += $item->quantity;
                $update->save();
            }

            SaleNote::where('id', $id)->delete();
            SaleNoteItem::where('sale_note_id', $id)->delete();
            Kardex::where('type', 'sale-note')
            ->where('sale_note_id',$id)->delete();

            return true;
        });

        return [
            'success' => true,
            'message' => 'Nota de venta eliminada con éxito'
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

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }
}