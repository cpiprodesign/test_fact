<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Document;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tenant.dashboard.index');
        // return view('tenant.documents.form');
    }

    public function counts_bank($mounth = 0)
    {
        $total_invoices = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', 1)
                    ->first();
    
        $total_charge = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', 0)
                    ->first();  

        return compact('total_invoices', 'total_charge');        
    }

    public function chart_cash_flow()
    {
        $documents = DB::connection('tenant')->select("SELECT DISTINCT(DATE(created_at)) AS created_at FROM documents ORDER BY created_at desc LIMIT 10");

        $labels = [];
        $data = [];

        $sql = "SELECT SUM(total) AS total FROM documents WHERE status_paid = ?  AND DATE(created_at) = ?";

        foreach($documents as $document)
        {
            $labels[] = $document->created_at;

            $cantidad = DB::connection('tenant')->select($sql, array(1, $document->created_at));
            $cantidad2 = DB::connection('tenant')->select($sql, array(0, $document->created_at));

            $data[] = (int)$cantidad[0]->total;
            $data2[] = (int)$cantidad2[0]->total;
        }

        $line = [
            'labels' => $labels,
            'data' => $data,
            'data2' => $data2
        ];

        return compact('line');
    }
}
