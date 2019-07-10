<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\DocumentExport;
use Illuminate\Http\Request;
use App\Traits\ReportTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\{
    Establishment,
    Company,
    Person
};
use Carbon\Carbon;

class ReportController extends Controller
{
    use ReportTrait;
    
    public function index() {

        $documentTypes = DocumentType::query()
            ->where('active', 1)
            ->get();

        $establishments = Establishment::all();
        
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

        return view('tenant.reports.index', compact('documentTypes', 'customers', 'establishments'));
    }
    
    public function search(Request $request)
    {
        $documentTypes = DocumentType::all();
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
        $establishments = Establishment::all();
        $td = $this->getTypeDoc($request->document_type); 
        $customer_td = $this->getPerson($request->customer);
        $establishment_td = $this->getEstablishment($request->establishment);
        
        $d = $request->d;
        $a = $request->a;
        
        $reports = $this->records($d, $a, $td, $customer_td, $establishment_td);

        return view("tenant.reports.index", compact("reports", "a", "d", "td", "documentTypes", "customers", "customer_td", "establishments", "establishment_td"));
    }
    
    public function pdf(Request $request)
    {        
        $d = $request->d;
        $a = $request->a;
        $td = $request->td;
        $customer_td = $request->customer_td;
        $establishment_td = $request->establishment_td;        
        
        $company = Company::first();
        $establishment = Establishment::where('id', $establishment_td)->first();
        
        $reports = $this->records($d, $a, $td, $customer_td, $establishment_td);
        
        $pdf = PDF::loadView('tenant.reports.report_pdf', compact("reports", "company", "establishment"));
        $filename = 'Reporte_Documentos'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }
    
    public function excel(Request $request)
    {
        $d = $request->d;
        $a = $request->a;
        $td = $request->td;
        $customer_td = $request->customer_td;
        $establishment_td = $request->establishment_td;        
        
        $company = Company::first();
        $establishment = Establishment::where('id', $establishment_td)->first();
        
        $records = $this->records($d, $a, $td, $customer_td, $establishment_td);
        
        return (new DocumentExport)
                ->excel_view()
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('ReporteDoc'.Carbon::now().'.xlsx');
    }

    public function records($d, $a, $document_type_id, $customer_id, $establishment_id)
    {
        $condicion = "";

        if($d != null && $a != null)
        {
            $condicion .= "AND doc.date_of_issue BETWEEN '".$d."' AND '".$a."'";
        }

        if(!is_null($document_type_id))
        {
            $condicion .= " AND doc.document_type_id = '".$document_type_id."'";
        }

        if(!is_null($customer_id))
        {
            $condicion .= " AND doc.customer_id = $customer_id";
        }

        if(!is_null($establishment_id))
        {
            $condicion .= " AND doc.establishment_id = $establishment_id";
        }

        $sql = "SELECT est.`description` AS establishment, doc.`document_type_id`, tid.`description` AS document_type, doc.`series`, doc.`number`, 
                doc.`date_of_issue`, per.`name`, per.`number` AS document_number, stt.`description` AS status_type, 
                doc.`status_paid`, doc.`total_taxed`, doc.`total_igv`, doc.`total`
                FROM documents doc
                INNER JOIN establishments est ON est.id = doc.`establishment_id`
                INNER JOIN cat_document_types tid ON tid.`id` = doc.`document_type_id`
                INNER JOIN persons per ON per.`id` = doc.`customer_id`
                INNER JOIN state_types stt ON stt.`id` = doc.`state_type_id`
                WHERE establishment_id IS NOT NULL $condicion";

        $items = DB::connection('tenant')->select($sql);

        return $items;
    }
}
