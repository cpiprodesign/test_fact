<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\DocumentCollection;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\ExpenseExport;
use Illuminate\Http\Request;
use App\Traits\ReportTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\{
    Establishment,
    User,
    Company,
    Person,
};
use Carbon\Carbon;

class ReportExpenseController extends Controller
{
    use ReportTrait;
    
    public function index() {

        $users = User::all();

        $establishments = Establishment::all();

        return view('tenant.reports.expenses.index', compact('users', 'establishments'));
    }
    
    public function search(Request $request)
    {
        $users = User::all();
        $establishments = Establishment::all();
        
        $user_td = $this->getUser($request->user);
        $establishment_td = $this->getEstablishment($request->establishment);
        
        $d = $request->d;
        $a = $request->a;
        
        $reports = $this->records($d, $a, $user_td, $establishment_td);

        return view("tenant.reports.expenses.index", compact("reports", "a", "d", "users", "user_td", "establishments", "establishment_td"));
    }
    
    public function pdf(Request $request) {        
        $d = $request->d;
        $a = $request->a;
        
        $user_td = $this->getUser($request->user);
        $establishment_td = $request->establishment_td;        
        
        $company = Company::first();
        $establishment = Establishment::where('id', $establishment_td)->first();
        
        $reports = $this->records($d, $a, $user_td, $establishment_td);
        
        $pdf = PDF::loadView('tenant.reports.expenses.report_pdf', compact("reports", "company", "establishment"));
        $filename = 'Reporte_Gastos'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }
    
    public function excel(Request $request) {
        $d = $request->d;
        $a = $request->a;
        $td = $request->td;
        $user_td = $this->getUser($request->user);
        $establishment_td = $request->establishment_td;        
        
        $company = Company::first();
        $establishment = Establishment::where('id', $establishment_td)->first();
        
        $records = $this->records($d, $a, $user_td, $establishment_td);
        
        return (new ExpenseExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('ReporteGastos'.Carbon::now().'.xlsx');
    }

    public function records($d, $a, $user_id, $establishment_id)
    {
        $condicion = "";

        if($d != null && $a != null)
        {
            $condicion .= "AND expe.date_of_issue BETWEEN '".$d."' AND '".$a."'";
        }

        if(!is_null($user_id))
        {
            $condicion .= " AND expe.user_id = $user_id";
        }

        if(!is_null($establishment_id))
        {
            $condicion .= " AND expe.establishment_id = $establishment_id";
        }

        $sql = "SELECT est.`description` AS establishment, user.name as user, expe.`date_of_issue`, expe.description, expe.`total`
                FROM expenses expe
                INNER JOIN establishments est ON est.id = expe.`establishment_id`
                INNER JOIN users user ON user.`id` = expe.`user_id`
                WHERE expe.establishment_id IS NOT NULL $condicion";

        $items = DB::connection('tenant')->select($sql);

        return $items;
    }
}
