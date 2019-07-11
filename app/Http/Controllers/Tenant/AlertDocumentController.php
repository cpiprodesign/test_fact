<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Document;
use App\Models\Tenant\Payment;
use App\Models\Tenant\SaleNote;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PaymentRequest;
use App\Http\Resources\Tenant\DocumentCollection;
use Illuminate\Http\Request;
use App\Models\Tenant\Pos;
use Illuminate\Support\Facades\DB;

class AlertDocumentController extends Controller
{
    public function index()
    {
        return view('tenant.alerts.documents.index');
    }

    public function columns()
    {
        return [
            'description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        $date = date("Y-m-d");
        $records = DB::connection('tenant')->select("SELECT doc.id, per.name, per.number as customer_number, doc.`document_type_id`, doc.`series`, doc.`number`, 
                doc.`date_of_issue`, doc.`total`, 7 - DATEDIFF('".$date."', doc.`date_of_issue`) as diferent
                    FROM documents doc
                    INNER JOIN persons per ON per.id = doc.`customer_id`
                    WHERE doc.`state_type_id` = '01' AND DATEDIFF('".$date."', doc.`date_of_issue`) > 3
                    ORDER BY doc.`date_of_issue` desc");

        return compact('records');
    }

    public static function notifications(Request $request)
    {
        $record = DB::connection('tenant')->select("SELECT COUNT(*) AS quantity
                    FROM documents doc
                    WHERE doc.`state_type_id` = '01' AND DATEDIFF(CURRENT_DATE(), doc.`date_of_issue`) > 3
                    ORDER BY doc.`date_of_issue` desc")->first();

        return $record;
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $currency_types = CurrencyType::whereActive()->orderByDescription()->get();
        $payment_methods = PaymentMethod::whereActive()->get();
        $accounts = Account::all();

        return compact('currency_types', 'accounts', 'payment_methods');
    }

    public function record($id)
    {
        $record = new AccountResource(Account::findOrFail($id));

        return $record;
    }
}