<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Establishment;
use App\Models\Tenant\Person;
use App\Models\Tenant\Company;
use App\Http\Controllers\Controller;
use App\Exports\DocumentExport;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ReportTrait;
use Carbon\Carbon;

class ReportCustomerController extends Controller
{
    use ReportTrait;

    public function index()
    {
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

        return view('tenant.reports.customers.index', compact('establishments', 'customers'));
    }

    public function detail(Person $person)
    {
        $totals = DB::connection('tenant')->table('persons as per')
        ->select(DB::raw('(SELECT SUM(total) FROM documents AS doc WHERE doc.customer_id = per.id) AS total'),
            DB::raw('(SELECT SUM(total) FROM sale_notes AS san WHERE san.customer_id = per.id) AS total2'),
            DB::raw('(SELECT SUM(total_paid) FROM documents AS doc WHERE doc.customer_id = per.id) AS total_paid'),
            DB::raw('(SELECT SUM(total_paid) FROM sale_notes AS doc WHERE doc.customer_id = per.id) AS total_paid2'))
        ->where('per.type', 'customers')
        ->where('per.id', $person->id)
        ->first();

        return view('tenant.reports.customers.detail', compact('person', 'totals'));
    }    

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número'
        ];
    }

    public function search(Request $request)
    {
        $d = $request->d;
        $a = $request->a;
        $customer_td = $this->getPerson($request->customer);
        $establishment_td = $this->getEstablishment($request->establishment);

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

        $records = $this->records($d, $a, $customer_td, $establishment_td);
        
        return view('tenant.reports.customers.index', compact('records', 'd', 'a', 'customer_td', 'customers', 'establishment_td', 'establishments'));
    }

    public function pdf(Request $request)
    {        
        $d = $request->d;
        $a = $request->a;
        $customer_td = $request->customer_td;
        $establishment_td = $request->establishment_td;
        
        $company = Company::first();
        $establishment = Establishment::where('id', $establishment_td)->first();
        
        $records = $this->records($d, $a, $customer_td, $establishment_td);
        
        $pdf = PDF::loadView('tenant.reports.customers.report_pdf', compact("records", "company", "establishment"));
        $filename = 'Reporte_Ventas_Cliente'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }

    public function excel(Request $request)
    {
        $d = $request->d;
        $a = $request->a;
        $customer_td = $request->customer_td;
        $establishment_td = $request->establishment_td;      
        
        $company = Company::first();
        $establishment = Establishment::where('id', $establishment_td)->first();
        
        $records = $this->records($d, $a, $customer_td, $establishment_td);
        
        return (new DocumentExport)
                ->excel_view('tenant.reports.customers.report_excel')
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('ReporteVentasCliente'.Carbon::now().'.xlsx');
    }

    public function records($d, $a,  $customer_id, $establishment_id)
    {
        $condition = "";

        if($d != null && $a != null)
        {
            $condition .= "AND doc.date_of_issue BETWEEN '".$d."' AND '".$a."'";
        }

        if(!is_null($establishment_id))
        {
            $condition .= " AND doc.establishment_id = $establishment_id";
        }

        if(!is_null($customer_id))
        {
            $condition .= " AND doc.customer_id = $customer_id";
        }

        $sql = "SELECT customer_id, name, number, SUM(quantity) AS quantity, SUM(total) AS total, SUM(total_paid) AS total_paid
                FROM (SELECT doc.customer_id, COUNT(*) AS quantity,  per.`name`, per.`number`, SUM(doc.`total`) AS total, SUM(doc.`total_paid`) AS total_paid
                FROM documents doc
                INNER JOIN persons per ON per.id = doc.`customer_id`
                WHERE (doc.`document_type_id` = '01' OR doc.`document_type_id` = '03')
                AND (doc.`state_type_id` = '01' OR doc.`state_type_id` = '03' OR doc.`state_type_id` = '05' OR doc.`state_type_id` = '07') 
                AND per.`type` = 'customers' $condition
                GROUP BY doc.`customer_id`, per.`name`, per.`number`
                UNION ALL
                SELECT doc.customer_id, COUNT(*) AS quantity, per.`name`, per.number, SUM(doc.`total`) AS total, SUM(doc.`total_paid`) AS total_paid
                FROM sale_notes doc
                INNER JOIN persons per ON per.id = doc.`customer_id`
                WHERE per.`type` = 'customers' $condition
                GROUP BY doc.`customer_id`, per.`name`, per.`number`) AS report
                GROUP BY customer_id, name, number";
        
        $records = DB::connection('tenant')->select($sql);

        return $records;
    }
}
