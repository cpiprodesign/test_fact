<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\DocumentCollection;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\DocumentExport;
use Illuminate\Http\Request;
use App\Traits\ReportTrait;
use App\Models\Tenant\{
    Establishment,
    Document,
    Company,
    Person,
};
use Carbon\Carbon;

class ReportController extends Controller
{
    use ReportTrait;
    
    public function index() {

        $documentTypes = DocumentType::query()
            ->where('active', 1)
            ->get();
        
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

        return view('tenant.reports.index', compact('documentTypes', 'customers'));
    }
    
    public function search(Request $request) {
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
        
        $td = $this->getTypeDoc($request->document_type); 
        $customer_td = $this->getPerson($request->customer);
        $d = null;
        $a = null;
        
        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) 
        {
            $d = $request->d;
            $a = $request->a;
            
            $reports = $this->getReports2($td, $customer_td, $d, $a);  
        }
        else 
        {
            $reports = $this->getReports($td, $customer_td);   
        }
        
        return view("tenant.reports.index", compact("reports", "a", "d", "td", "documentTypes", "customers", "customer_td"));
    }
    
    public function pdf(Request $request) {
        $company = Company::first();
        $establishment = Establishment::first();
        $td = $request->td;
        $customer_td = $request->customer_td;

        //echo $customer_td; exit;
        
        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;
            
            $reports = $this->getReports2($td, $customer_td, $d, $a);
        }
        else {
            $reports = $this->getReports($td, $customer_td);   
        }
        
        $pdf = PDF::loadView('tenant.reports.report_pdf', compact("reports", "company", "establishment"));
        $filename = 'Reporte_Documentos'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }
    
    public function excel(Request $request) {
        $company = Company::first();
        $establishment = Establishment::first();
        $td= $request->td;
        $customer_td = $request->customer_td;
       
        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;
            
            $records = $this->getReports2($td, $customer_td, $d, $a);
        }
        else {            
            
            $records = $this->getReports($td, $customer_td);            
        }
        
        return (new DocumentExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('ReporteDoc'.Carbon::now().'.xlsx');
    }

    public function getReports($td, $customer_td)
    {
        if (is_null($td) && is_null($customer_td))
        {
            $reports = Document::with(['state_type', 'person'])
                ->latest()
                ->get();
        }
        else if(!is_null($td) && is_null($customer_td))
        {
            $reports = Document::with([ 'state_type', 'person'])
                ->latest()
                ->where('document_type_id', $td)
                ->get();
        }
        else if(is_null($td) && !is_null($customer_td))
        {
            $reports = Document::with([ 'state_type', 'person'])
                ->latest()
                ->where('customer_id', $customer_td)
                ->get();
        }
        else 
        {
            $reports = Document::with([ 'state_type', 'person'])
                ->latest()
                ->where('document_type_id', $td)
                ->where('customer_id', $customer_td)
                ->get();
        }

        return $reports;
    }

    public function getReports2($td, $customer_td, $d, $a)
    {
        if (is_null($td) && is_null($customer_td))
        {
            $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
        }
        else if(!is_null($td) && is_null($customer_td))
        {
            $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->get();
        }
        else if(is_null($td) && !is_null($customer_td))
        {
            $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('customer_id', $customer_td)
                    ->get();
        }
        else 
        {
            $reports = Document::with([ 'state_type', 'person'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('document_type_id', $td)
                    ->where('customer_id', $customer_td)
                    ->get();
        }

        return $reports;
    }
}
