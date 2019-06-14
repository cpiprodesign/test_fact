<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Module;

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
        $modules = $this->permission_modules();

        if(in_array('dashboard', $modules))
        {
            return view('tenant.dashboard.index');
        }
        else
        {
            return view('tenant.documents.form');
        }
    }

    public function sells()
    {
        $modules = $this->permission_modules();

        if(in_array('dashboard', $modules))
        {
            return view('tenant.dashboard.sells');
        }
        else
        {
            return redirect('dashboard');
        }
    }

    public function establishments()
    {
        $establishments = Establishment::all();

        return compact('establishments'); 
    }

    public function load($establishment_id = 0)
    {
        $establishment_id = (int)$establishment_id;

        if($establishment_id == 0)
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
            
            $total_sell = DB::connection('tenant')
                ->table('documents')
                ->select(DB::raw('SUM(total) as total'))
                ->where('status_paid', 1)
                ->where(DB::raw('DATE(created_at)'), date("Y-m-d"))
                ->first();            

            $sql = "SELECT ite.`description`, itw.stock, ite.`stock_min`,
                (SELECT description FROM warehouses war WHERE war.id = itw.warehouse_id LIMIT 1) AS warehouse
                FROM items ite
                INNER JOIN item_warehouse itw ON itw.item_id = ite.id
                WHERE itw.stock < stock_min + 9";

            $items = DB::connection('tenant')->select($sql, array($establishment_id));

            $sql = "SELECT per.name, doc.`date_of_issue`, doc.total, doc.series, doc.number, doc.id, doc.document_type_id, 'documents' AS resource
                FROM documents doc
                INNER JOIN persons per ON per.id = doc.customer_id
                WHERE (total_paid < total)
                UNION ALL
                SELECT per.name, san.`date_of_issue`, san.total, san.series, san.number, san.id, san.document_type_id, 'sale-notes' AS resource
                FROM sale_notes san
                INNER JOIN persons per ON per.id = san.customer_id
                WHERE (total_paid < total)
                ORDER BY date_of_issue";

            $customers = DB::connection('tenant')->select($sql, array($establishment_id));
        }
        else 
        {
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            $warehouse_id = $warehouse->id;

            $total_invoices = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('total_paid', '=', 'total')
                    ->where('establishment_id', $establishment_id)
                    ->first();
    
            $total_charge = DB::connection('tenant')
                        ->table('documents')
                        ->select(DB::raw('SUM(total) as total'))
                        ->where('total_paid', '<', 'total')
                        ->where('establishment_id', $establishment_id)
                        ->first(); 
            
            $total_sell = DB::connection('tenant')
                ->table('documents')
                ->select(DB::raw('SUM(total) as total'))
                ->where('status_paid', 1)
                ->where(DB::raw('DATE(created_at)'), date("Y-m-d"))
                ->where('establishment_id', $establishment_id)
                ->first();

            $sql = "SELECT ite.`description`, itw.stock, ite.`stock_min`,
                (SELECT description FROM warehouses war WHERE war.id = itw.warehouse_id LIMIT 1) AS warehouse
                FROM items ite
                INNER JOIN item_warehouse itw ON itw.item_id = ite.id
                WHERE itw.warehouse_id = ? AND itw.stock < stock_min + 9";

            $items = DB::connection('tenant')->select($sql, array($establishment_id));

            $sql = "SELECT per.name, doc.`date_of_issue`, doc.total, doc.series, doc.number, doc.id, doc.document_type_id, 'documents' AS resource
                    FROM documents doc
                    INNER JOIN persons per ON per.id = doc.customer_id
                    WHERE (total_paid < total) AND doc.establishment_id = ?
                    UNION ALL
                    SELECT per.name, san.`date_of_issue`, san.total, san.series, san.number, san.id, san.document_type_id, 'sale-notes' AS resource
                    FROM sale_notes san
                    INNER JOIN persons per ON per.id = san.customer_id
                    WHERE (total_paid < total) AND san.establishment_id = ?
                    ORDER BY date_of_issue";

            $customers = DB::connection('tenant')->select($sql, array($establishment_id));
        }

        $total_invoices = (int)$total_invoices->total;
        $total_charge = (int)$total_charge->total;
        $total_sell = (int)$total_sell->total;

        // $line = $this->chart_cash_flow($establishment_id);

        return compact('total_invoices', 'total_charge', 'total_sell', 'items', 'customers'); 
    }

    public function total($establishment_id = 0, $range="Diario", $status_paid = 1)
    {
        if($establishment_id == 0)
        {
            if($range == 'Diario')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', $status_paid)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
            }
            else if($range == 'Mensual')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', $status_paid)
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->first();
            }
            else if($range == 'Anual')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', $status_paid)
                    ->whereYear('created_at', date('Y'))
                    ->first();
            }
            else
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', $status_paid)
                    ->first();
            }            
        }
        else
        {
            if($range == 'Diario')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', $status_paid)
                    ->where('establishment_id', $establishment_id)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
            }
            else if($range == 'Mensual')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', $status_paid)
                    ->where('establishment_id', $establishment_id)
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->first();
            }
            else if($range == 'Anual')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', $status_paid)
                    ->where('establishment_id', $establishment_id)
                    ->whereYear('created_at', date('Y'))
                    ->first();
            }
            else
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('status_paid', $status_paid)
                    ->where('establishment_id', $establishment_id)                    
                    ->first();
            }
        }       

        return $total;
    }

    public function load_sells($establishment_id = 0, $range="Diario")
    {
        if($establishment_id == 0)
        {
            if($range == 'Diario')
            {
                $sells = Document::whereDate('created_at', date('Y-m-d'))->get();
            }
            else if($range == 'Mensual')
            {
                $sells = Document::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get();
            }
            else
            {
                $sells = Document::whereYear('created_at', date('Y'))->get();
            }
        }
        else 
        {
            if($range == 'Diario')
            {
                $sells = Document::where('establishment_id', $establishment_id)->whereDate('created_at', date('Y-m-d'))->get();
            }
            else if($range == 'Mensual')
            {
                $sells = Document::where('establishment_id', $establishment_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get();
            }
            else
            {
                $sells = Document::where('establishment_id', $establishment_id)->whereYear('created_at', date('Y'))->get();
            }                      
        }

        $total_invoices = (int)$this->total($establishment_id, $range, 1)->total;
        $total_charges = (int)$this->total($establishment_id, $range, 0)->total;
        
        return compact('sells', 'total_invoices', 'total_charges');
    }

    public function chart_cash_flow($establishment_id)
    {
        $labels = [];
        $data = [];
        $data2 = [];

        if($establishment_id == 0)
        {
            $documents = DB::connection('tenant')->select("SELECT DISTINCT(DATE(created_at)) AS created_at FROM documents ORDER BY created_at desc LIMIT 10");

            $sql = "SELECT SUM(total) AS total FROM documents WHERE status_paid = ?  AND DATE(created_at) = ?";

            foreach($documents as $document)
            {
                $labels[] = $document->created_at;

                $cantidad = DB::connection('tenant')->select($sql, array(1, $document->created_at));
                $cantidad2 = DB::connection('tenant')->select($sql, array(0, $document->created_at));

                $data[] = (int)$cantidad[0]->total;
                $data2[] = (int)$cantidad2[0]->total;
            }
        }
        else 
        {
            $documents = DB::connection('tenant')->select("SELECT DISTINCT(DATE(created_at)) AS created_at FROM documents WHERE establishment_id = $establishment_id ORDER BY created_at desc LIMIT 10");

            $sql = "SELECT SUM(total) AS total FROM documents WHERE establishment_id = ? AND status_paid = ?  AND DATE(created_at) = ?";

            foreach($documents as $document)
            {
                $labels[] = $document->created_at;

                $cantidad = DB::connection('tenant')->select($sql, array($establishment_id, 1, $document->created_at));
                $cantidad2 = DB::connection('tenant')->select($sql, array($establishment_id, 0, $document->created_at));

                $data[] = (int)$cantidad[0]->total;
                $data2[] = (int)$cantidad2[0]->total;
            }
        }        

        $line = [
            'labels' => $labels,
            'data' => $data,
            'data2' => $data2
        ];

        return compact('line'); 
    }

    public function chart_pie_total($establishment_id)
    {
        $labels = [];
        $data = [];

        $labels = ["Total Pagado", "Total Pendiente"];

        $total_invoices = (int)$this->total($establishment_id, "", 1)->total;
        $total_charge = (int)$this->total($establishment_id, "", 0)->total;

        $data = [$total_invoices, $total_charge];        

        $pie = [
            'labels' => $labels,
            'data' => $data
        ];

        return compact('pie'); 
    }

    public function permission_modules()
    {
        $modules = auth()->user()->modules()->pluck('value')->toArray();

        if(count($modules) > 0) 
        {
            $vc_modules = $modules;
        } 
        else 
        {
            $vc_modules = Module::all()->pluck('value')->toArray();
        }

        return $vc_modules;
    }
}
