<?php

namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Resources\Tenant\DispatchCollection;
use App\Http\Controllers\Controller;
use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\Catalogs\{
    IdentityDocumentType,
    TransferReasonType,
    TransportModeType,
    Department,
    Province,
    District,
    UnitType,
    Country
};
use Illuminate\Http\Request;
use App\Models\Tenant\{
    Establishment,
    Document,
    DocumentItem,
    Dispatch,
    Person,
    Series,
    Item
};
use Exception, DB;
use Illuminate\Support\Facades\Storage;

class DispatchController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('input.request:dispatch,web', ['only' => ['store']]);
    }

    public function index()
    {
        return view('tenant.dispatches.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Dispatch::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('series')
            ->orderBy('number', 'desc');

        return new DispatchCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function create()
    {
        return view('tenant.dispatches.form');
    }

    public function create2($document_id = false)
    {
        return view('tenant.dispatches.form2', compact('document_id'));
    }

    public function store(Request $request)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($request) {
            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->createPdf();
            return $facturalo;
        });

        $document = $fact->getDocument();

        $document->has_xml = 1;
        $document->has_pdf = 1;

        try {

            $fact->senderXmlSignedBill();
//            $response = $fact->getResponse();
            $document->has_cdr = 1;
            $document->save();

            return [
                'success' => true,
                'message' => "Se creo la guía de remisión {$document->series}-{$document->number}",
            ];

        } catch (Exception $e) {

            $document->has_cdr = 0;
            $document->save();

            $message = $e->getMessage();
            return [
                'success' => true,
                'message' => "Se creo la guía de remisión {$document->series}-{$document->number} sin CDR \nServicio Sunat: \"{$message}\"",
            ];

        }

    }

    // reenvio y captura del cdr
    public function resend($document_id)
    {

        $document = Dispatch::find($document_id);
        $response = [];

        try {
            $fileXML = "signed/{$document->filename}.xml";
            $has_xml_file = Storage::disk("tenant")->exists($fileXML);

            $filePDF = "pdf/{$document->filename}.pdf";
            $has_pdf_file = Storage::disk("tenant")->exists($filePDF);

            $fileCDR = "cdr/R-{$document->filename}.zip";
            $has_cdr_file = Storage::disk("tenant")->exists($fileCDR);


            $facturalo = DB::connection('tenant')->transaction(function () use ($document) {
                return new Facturalo();
            });
            $facturalo->setDocument($document);
            $facturalo->setType('dispatch');

            if (!$has_xml_file) { // corrige la existencia de xm
                $facturalo->createXmlUnsigned();
                $facturalo->signXmlUnsigned();
            }
            $document->has_xml = 1;

            if (!$has_pdf_file) { //corrige la existencia de pdf
                $facturalo->createPdf(null, null, 'a4');
            }
            $document->has_pdf = 1;

            if (!$has_cdr_file) { // corrige la existencia de cdr
                try {
                    $facturalo->consultCdr();
                    $document->has_cdr = 1;
                } catch (Exception $e) {
                    $facturalo->loadXmlSigned(); // se carga el xml firmado
                    $facturalo->onlySenderXmlSignedBill();
                }
                $response = $facturalo->getResponse();
            } else {
                $response = ['description' => 'Se ha corregido el índice del archivo CDR'];
                $document->has_cdr = 1;
            }

            $document->save();

            return [
                'success' => true,
                'message' => $response['description'],
            ];

        } catch (Exception $e) {

            $status = false;

            if (preg_match('/Code: 4000;/', $e->getMessage())) {
                $status = true;
                $document->has_cdr = 2;
                $document->save();
            }

            return [
                'success' => $status,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];

        }

    }

    public function datos($document_id)
    {
        $document = Document::where('id', $document_id)->first();
        $person = Person::where('id', $document->customer_id)->first();
        $series = Series::where('establishment_id', $document->establishment_id)->where('document_type_id', '09')->get();

        $document_items = DocumentItem::where('document_id', $document_id)->get();

        $items = array();

        foreach ($document_items as $document_item) {
            $row = Item::whereId($document_item->item_id)->first();

            $items[] = [
                'id' => $row->id,
                'description' => $row->description,
                'quantity' => $document_item->quantity
            ];
        }

        return compact('document', 'series', 'items');
    }

    /**
     * Tables
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function tables(Request $request)
    {
        $items = Item::query()
            ->where('item_type_id', '01')
            ->orderBy('description')
            ->get()
            ->transform(function ($row) {
                $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;

                return [
                    'id' => $row->id,
                    'full_description' => $full_description,
                    'description' => $row->description,
                    'internal_id' => $row->internal_id,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => $row->sale_unit_price,
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id
                ];
            });

        $customers = Person::query()
            ->whereIn('identity_document_type_id', [6])
            ->whereType('customers')
            ->orderBy('name')
            ->get()
            ->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number . ' - ' . $row->name,
                    'name' => $row->name,
                    'trade_name' => $row->trade_name,
                    'country_id' => $row->country_id,
                    'address' => $row->address,
                    'email' => $row->email,
                    'telephone' => $row->telephone,
                    'number' => $row->number,
                    'district_id' => $row->district_id,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code
                ];
            });

        $identityDocumentTypes = IdentityDocumentType::whereActive()->get();
        $transferReasonTypes = TransferReasonType::whereActive()->get();
        $transportModeTypes = TransportModeType::whereActive()->get();
        $departments = Department::whereActive()->get();
        $provinces = Province::whereActive()->get();
        $unitTypes = UnitType::whereActive()->get();
        $countries = Country::whereActive()->get();
        $districts = District::whereActive()->get();
        $establishments = Establishment::all();
        $series = Series::all();

        return compact('establishments', 'customers', 'series', 'transportModeTypes', 'transferReasonTypes', 'unitTypes', 'countries', 'departments', 'provinces', 'districts', 'identityDocumentTypes', 'items');
    }

    public function downloadExternal($type, $external_id)
    {
        $retention = Dispatch::where('external_id', $external_id)->first();

        if (!$retention) {
            throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        }

        switch ($type) {
            case 'pdf':
                $folder = 'pdf';
                break;
            case 'xml':
                $folder = 'signed';
                break;
            case 'cdr':
                $folder = 'cdr';
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($retention->filename, $folder);
    }
}
