<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Module;
use Auth;

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
            $total_sells = $this->totals("AND doc.date_of_issue = '".date("Y-m-d")."'");
            
            $totals = $this->totals();
            
            $sql = "SELECT ite.`description`, itw.stock, ite.`stock_min`,
                (SELECT description FROM warehouses war WHERE war.id = itw.warehouse_id LIMIT 1) AS warehouse
                FROM items ite
                INNER JOIN item_warehouse itw ON itw.item_id = ite.id
                WHERE itw.stock < stock_min + 9";

            $items = DB::connection('tenant')->select($sql, array($establishment_id));

            $sql = "SELECT per.name, doc.`date_of_issue`, doc.total, doc.series, doc.number, doc.id, doc.document_type_id, 'documents' AS resource
                FROM documents doc
                INNER JOIN persons per ON per.id = doc.customer_id
                WHERE (total_paid < total) AND (doc.state_type_id != '09' AND doc.state_type_id != '11')
                UNION ALL
                SELECT per.name, san.`date_of_issue`, san.total, san.series, san.number, san.id, san.document_type_id, 'sale-notes' AS resource
                FROM sale_notes san
                INNER JOIN persons per ON per.id = san.customer_id
                WHERE (total_paid < total)
                ORDER BY date_of_issue";

            $customers = DB::connection('tenant')->select($sql);
        }
        else 
        {
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            $warehouse_id = $warehouse->id;

            $total_sells = $this->totals("AND doc.establishment_id = $establishment_id AND doc.date_of_issue = '".date("Y-m-d")."'");
            
            $totals = $this->totals("AND doc.establishment_id = $establishment_id");
    
            $sql = "SELECT ite.`description`, itw.stock, ite.`stock_min`,
                (SELECT description FROM warehouses war WHERE war.id = itw.warehouse_id LIMIT 1) AS warehouse
                FROM items ite
                INNER JOIN item_warehouse itw ON itw.item_id = ite.id
                WHERE itw.warehouse_id = ? AND itw.stock < stock_min + 9";

            $items = DB::connection('tenant')->select($sql, array($establishment_id));

            $condition = "AND doc.establishment_id = $establishment_id";

            $sql = "SELECT per.name, doc.`date_of_issue`, doc.total, doc.series, doc.number, doc.id, doc.document_type_id, 'documents' AS resource
                    FROM documents doc
                    INNER JOIN persons per ON per.id = doc.customer_id
                    WHERE (total_paid < total) AND (doc.state_type_id != '09' AND doc.state_type_id != '11') $condition
                    UNION ALL
                    SELECT per.name, doc.`date_of_issue`, doc.total, doc.series, doc.number, doc.id, doc.document_type_id, 'sale-notes' AS resource
                    FROM sale_notes doc
                    INNER JOIN persons per ON per.id = doc.customer_id
                    WHERE (total_paid < total) $condition
                    ORDER BY date_of_issue";

            $customers = DB::connection('tenant')->select($sql);
        }

        $total = number_format($totals->total, 2);
        $total2 = number_format($totals->total - $totals->total_paid, 2);
        $total_sells = number_format($total_sells->total, 2);

        return compact('total', 'total2', 'total_sells', 'items', 'customers'); 
    }

    public function total($establishment_id = 0, $range="Diario")
    {
        if($establishment_id == 0)
        {
            if($range == 'Diario')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->select(DB::raw('SUM(total_paid) as total_paid'))
                    ->whereIn('document_type_id', ['01', '03'])
                    ->whereIn('state_type_id', ['01', '03', '05', '07'])
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
            }
            else if($range == 'Mensual')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->first();
            }
            else if($range == 'Anual')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->whereYear('created_at', date('Y'))
                    ->first();
            }
            else
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
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
                    ->where('establishment_id', $establishment_id)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
            }
            else if($range == 'Mensual')
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
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
                    ->where('establishment_id', $establishment_id)
                    ->whereYear('created_at', date('Y'))
                    ->first();
            }
            else
            {
                $total = DB::connection('tenant')
                    ->table('documents')
                    ->select(DB::raw('SUM(total) as total'))
                    ->where('establishment_id', $establishment_id)                    
                    ->first();
            }
        }       

        return $total;
    }

    public function load_sells($establishment_id = 0, $range="Diario")
    {
        $condition = "";

        if($range == 'Diario')
        {
            $condition .= "AND doc.date_of_issue = '".date("Y-m-d")."'";

            $sells = Document::whereDate('created_at', date('Y-m-d'))->get();
        }
        else if($range == 'Mensual')
        {
            $condition .= "AND MONTH(doc.date_of_issue)= '".date("m")."' AND YEAR(doc.date_of_issue)= '".date("Y")."'";
        }
        else
        {
            $condition .= "AND YEAR(doc.date_of_issue)= '".date("Y")."'";
        }

        if($establishment_id != 0)
        {
            $condition .=  "AND doc.establishment_id = $establishment_id";
        }

        $sql = "SELECT rep.*, per.number AS customer_number, per.name
            FROM
            (SELECT doc.customer_id, doc.`total`, doc.`total_paid`, cdt.description AS `type`, doc.`created_at`, doc.`series`, doc.`number`, doc.currency_type_id
                        FROM documents doc
                        INNER JOIN cat_document_types cdt ON cdt.id = doc.document_type_id
                        WHERE (doc.`document_type_id` = '01' OR doc.`document_type_id` = '03')
                        AND (doc.`state_type_id` = '01' OR doc.`state_type_id` = '03' OR doc.`state_type_id` = '05' 
                        OR doc.`state_type_id` = '07') $condition
                        UNION ALL
                        SELECT doc.customer_id, doc.`total`, doc.`total_paid`, 'NOTA DE VENTA', doc.`created_at`, doc.`series`, doc.`number`, doc.currency_type_id
                        FROM sale_notes doc
                        WHERE doc.`document_type_id` = '100' $condition) rep
            INNER JOIN persons per ON per.id = rep.customer_id
            ORDER BY created_at DESC";

        $sells = DB::connection('tenant')->select($sql);

        $totals = $this->totals($condition);

        $total = $totals->total;
        $total_paid = $totals->total_paid;
        
        return compact('sells', 'total', 'total_paid');
    }

    public function chart_cash_flow($establishment_id)
    {
        $labels = [];
        $data = [];
        $data2 = [];

        $establishment_id = (int)$establishment_id;

        $condition = "";

        if($establishment_id != 0)
        {
            $condition .= "AND doc.establishment_id = $establishment_id";            
        }
        
        $documents = DB::connection('tenant')
            ->select("SELECT rep.`date_of_issue`, SUM(total) AS total, SUM(total_paid) AS total_paid
                            FROM
                            (SELECT doc.`date_of_issue`, doc.total, doc.total_paid
                            FROM documents doc
                            INNER JOIN cat_document_types cdt ON cdt.id = doc.document_type_id
                            WHERE (doc.`document_type_id` = '01' OR doc.`document_type_id` = '03')
                            AND doc.currency_type_id = 'PEN'
                            AND (doc.`state_type_id` = '01' OR doc.`state_type_id` = '03' OR doc.`state_type_id` = '05' OR doc.`state_type_id` = '07')
                            AND DATEDIFF(CURRENT_DATE(), doc.`date_of_issue`) < 8 $condition
                            UNION ALL
                            SELECT doc.`date_of_issue`, doc.total, doc.total_paid
                            FROM sale_notes doc
                            WHERE doc.currency_type_id = 'PEN' AND doc.`document_type_id` = '100' 
                            AND DATEDIFF(CURRENT_DATE(), doc.`date_of_issue`) < 8
                            ) AS rep
                GROUP BY rep.`date_of_issue` ORDER BY `date_of_issue`");

        foreach($documents as $document)
        {
            $labels[] = $document->date_of_issue;

            $data[] = $document->total;
            $data2[] = $document->total_paid;
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

        $condition = "";

        if($establishment_id != 0)
        {
            $condition .= "AND doc.establishment_id = $establishment_id";
        }

        $totals = $this->totals();

        $total_invoices = $totals->total_paid;
        $total_charge = $totals->total - $totals->total_paid;

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

    public function totals($condition = "", $currency_type_id = 'PEN')
    {
        $sql = "SELECT SUM(total) total, SUM(total_paid) total_paid
                FROM
                (SELECT doc.`total`, doc.`total_paid`
                FROM documents doc
                INNER JOIN cat_document_types cdt ON cdt.id = doc.document_type_id
                WHERE (doc.`document_type_id` = '01' OR doc.`document_type_id` = '03')
                AND doc.currency_type_id = '".$currency_type_id."'
                AND (doc.`state_type_id` = '01' OR doc.`state_type_id` = '03' OR doc.`state_type_id` = '05' 
                OR doc.`state_type_id` = '07') $condition
                UNION ALL
                SELECT doc.`total`, doc.`total_paid`
                FROM sale_notes doc
                WHERE doc.currency_type_id = '".$currency_type_id."' 
                AND doc.`document_type_id` = '100' $condition) AS rep";

        $totals = DB::connection('tenant')->select($sql)[0];

        return $totals;
    }
}
