<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\PosCollection;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Payment;
use App\Models\Tenant\Account;
use App\Models\Tenant\Pos;
use App\Models\Tenant\User;
use App\Models\Tenant\Catalogs\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Integer;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\SaleNote;

class PosController extends Controller
{
    public function index()
    {
        return view('tenant.pos.index');
    }

    public function tables()
    {
        $user = auth()->user();
        $pos = Pos::active();

        return compact('user', 'pos');
    }

    public function columns()
    {
        return [
            'establishment.description' => 'Ubicación',
            'user.name' => 'user'
        ];
    }

    public function records(Request $request)
    {
        if(auth()->user()->admin)
        {
            
            $records = Pos::orderBy('created_at', 'desc')->withTrashed();
        }
        else
        {
            $records = Pos::where('user_id', auth()->user()->establishment_id)->orderBy('created_at', 'desc')->withTrashed();
        }

        $request->column = explode('.', $request->column);

        $records->with([$request->column[0] => function ($q) use ($request) {
            $q->where($request->column[1], 'like', "%{$request->value}%");
        }]);

        return new PosCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function register()
    {
        $pos = Pos::active();
        
        return view('tenant.pos.register', compact('pos'));
    }

    public function store(Request $request)
    {
        return Pos::create($request->toArray());
    }

    public function destroy()
    {
        $pos_id = Pos::active();
        $pos = Pos::find($pos_id);
        $pos->status = 'close';
        $pos->save();
        return Pos::destroy($pos_id);
    }

    public function details($pos_id)
    {
        $sql = "SELECT dat.*, symbol FROM (SELECT tab.id, tab.series, tab.number, tab.`currency_type_id`, pay.total, 'Venta' AS operation_type, null as detail
                FROM payments pay
                INNER JOIN documents tab ON tab.id = pay.document_id
                WHERE pay.pos_id = $pos_id
                UNION ALL
                SELECT tab.id, tab.series, tab.number, tab.`currency_type_id`, pay.total, 'Nota de Venta' AS operation_type, null
                FROM payments pay
                INNER JOIN sale_notes tab ON tab.id = pay.sale_note_id
                WHERE pay.pos_id = $pos_id
                UNION ALL
                SELECT tab.id, '-', '-', tab.`currency_type_id`, - tab.`total`, 'Gasto', tab.description
                FROM pos_sales pos
                INNER JOIN expenses tab ON tab.id = pos.`document_id`
                WHERE table_name = 'expenses' AND pos.`pos_id` = $pos_id
            ) dat
            INNER JOIN cat_currency_types cur ON cur.id = dat.currency_type_id";

        $detail_box = DB::connection('tenant')->select($sql);

        $box = Pos::withTrashed()->find($pos_id);

        $user = User::find(auth()->id());

        $array = [
            'box' => $box,
            'detail_box' => $detail_box,
            'user' => $user
        ];

        return $array;
    }

    public function operations($document_id, Request $request)
    {
        $pos_id = Pos::active();
        $pos = Pos::find($pos_id);

        $pos->close_amount += $request->balance['total'];
        $pos->save();

        $pos_sale = $pos->sales()->create([
            'table_name' => $request->input('table_name'),
            'document_id' => $document_id,
            'total' => $request->balance['total'],
            'payed' => $request->balance['pagando'],
            'delta' => $request->balance['delta'],
        ]);

        $pos_sale->save();

        if($request->input('table_name') == 'documents')
        {
            $document = Document::find($document_id);
        }
        else
        {
            $document = SaleNote::find($document_id);
        }

        $document->total_paid += $request->balance['total'];
        $customer_id = $document->customer_id;
        $document->save();

        foreach ($request->sale as $detail) {

            $payment_method = PaymentMethod::where('description', $detail['tipo'])->first();

            $payment = new Payment();

            if($request->input('table_name') == 'documents')
            {
                $payment->document_id = $document_id;
            }
            else
            {
                $payment->sale_note_id = $document_id;
            }

            $payment->customer_id = $customer_id;
            $payment->payment_method_id = $payment_method->id;
            $payment->date_of_issue = date("Y-m-d");
            $payment->currency_type_id = 'PEN';
            $payment->account_id = 1;
            $payment->description = '';
            $payment->total = $request->balance['total'];
            $payment->pos_id = $pos_id;
            $payment->save();
        }

        $account = Account::find(1);
        $account->current_balance += $request->balance['total'];
        $account->save();
    }

    public function pdf($pos_id) {
        $company = Company::first();
        $pos = $this->details($pos_id);

        $pdf = PDF::loadView('tenant.reports.pos.report_pdf', compact("company", 'pos'));
        $filename = 'Reporte_Pos'.$pos['box']->created_at->format('_Ymd_Hm');

        return $pdf->download($filename.'.pdf');
    }
}
