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

class ReportSellController extends Controller
{
    use ReportTrait;

    public function index()
    {
        $establishments = Establishment::all();

        return view('tenant.reports.sells.index', compact('establishments'));
    }

    public function search(Request $request)
    {
        $d = $request->d;
        $a = $request->a;
        $establishment_td = $this->getEstablishment($request->establishment);;

        $establishments = Establishment::all();

        $records = $this->records($d, $a, $establishment_td);
        
        return view('tenant.reports.sells.index', compact('records', 'd', 'a', 'establishment_td', 'establishments'));
    }

    public function pdf(Request $request)
    {
        $d = $request->d;
        $a = $request->a;
        $establishment_td = $request->establishment_td;
        
        $company = Company::first();
        $establishment = Establishment::where('id', $establishment_td)->first();
        
        $records = $this->records($d, $a, $establishment_td);
        
        $pdf = PDF::loadView('tenant.reports.sells.report_pdf', compact("records", "company", "establishment"));
        $filename = 'Reporte_Ventas'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }

    public function excel(Request $request)
    {
        $d = $request->d;
        $a = $request->a;
        $establishment_td = $request->establishment_td;
        
        $company = Company::first();
        $establishment = Establishment::where('id', $establishment_td)->first();
        
        $records = $this->records($d, $a, $establishment_td);
        
        return (new DocumentExport)
                ->excel_view('tenant.reports.sells.report_excel')
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('ReporteVentas'.Carbon::now().'.xlsx');
    }

    public function records($d, $a, $establishment_id)
    {
        $condition = "";

        if($d != null && $a != null)
        {
            $condition .= "AND doc.date_of_issue BETWEEN '".$d."' AND '".$a."'";
        }

        if(!is_null($establishment_id))
        {
            $establishment_id = (int)$establishment_id;
            $condition .= " AND doc.establishment_id = $establishment_id";
        }

        $sql = "SELECT rep.*, per.number as document_number, per.name
                FROM(
                SELECT doc.customer_id, doc.`total`, doc.`total_paid`, cdt.description AS type, doc.`date_of_issue`, doc.`series`, doc.`number`
                FROM documents doc
                INNER JOIN cat_document_types cdt ON cdt.id = doc.document_type_id
                WHERE (doc.`document_type_id` = '01' OR doc.`document_type_id` = '03')
                AND (doc.`state_type_id` = '01' OR doc.`state_type_id` = '03' OR doc.`state_type_id` = '05' OR doc.`state_type_id` = '07')
                $condition
                UNION ALL
                SELECT doc.customer_id, doc.`total`, doc.`total_paid`, 'NOTA DE VENTA', doc.`date_of_issue`, doc.`series`, doc.`number`
                FROM sale_notes doc
                WHERE doc.`document_type_id` = '100' $condition) AS rep
                INNER JOIN persons per ON per.id = rep.`customer_id`
                ORDER BY rep.date_of_issue, rep.series, rep.number DESC";
        
        $records = DB::connection('tenant')->select($sql);

        return $records;
    }
}
