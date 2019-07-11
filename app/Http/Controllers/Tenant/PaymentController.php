<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\PaymentMethod;
use App\Models\Tenant\Account;
use App\Models\Tenant\Document;
use App\Models\Tenant\Payment;
use App\Models\Tenant\SaleNote;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PaymentRequest;
use App\Http\Resources\Tenant\PaymentCollection;
use Exception;
use Illuminate\Http\Request;
use App\Models\Tenant\Pos;
use App\Models\Tenant\PosSalesDetails;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

class PaymentController extends Controller
{
    public function index()
    {
        return view('tenant.payments.index');
    }

    public function columns()
    {
        return [
           // 'payments.description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        //consulta en sql
        // $sql = "SELECT dat.*, symbol, cpm.`description` AS payment_method, acc.`name` AS account FROM 
        // (SELECT tab.id, tab.series, tab.number, tab.`currency_type_id`, payment_method_id, account_id, tab.total, 'Venta' AS operation_type, NULL AS detail
        // FROM payments pay
        // INNER JOIN documents tab ON tab.id = pay.document_id
        // UNION ALL
        // SELECT tab.id, tab.series, tab.number, tab.`currency_type_id`, payment_method_id, account_id, tab.total, 'Nota de Venta' AS operation_type, NULL
        // FROM payments pay
        // INNER JOIN sale_notes tab ON tab.id = pay.sale_note_id
        // ) dat
        // INNER JOIN cat_currency_types cur ON cur.id = dat.currency_type_id
        // INNER JOIN cat_payment_methods cpm ON cpm.`id` = dat.payment_method_id
        // INNER JOIN accounts acc ON acc.id = dat.account_id";

        $payment_documents = Payment::where('payments.total', '>', 0)
            ->select('payments.id', 'documents.series', 'documents.number', 'payments.currency_type_id', 'payments.total', 'cat_payment_methods.description AS payment_method', 
            'accounts.name AS account', 'symbol', 'persons.name AS customer', 'payments.created_at', 'payments.pos_id')
            ->join('documents', 'documents.id', 'payments.document_id')
            ->join('persons', 'persons.id', 'payments.customer_id')
            ->join('cat_payment_methods', 'cat_payment_methods.id', 'payments.payment_method_id')
            ->join('accounts', 'accounts.id', 'payments.account_id')
            ->join('cat_currency_types', 'cat_currency_types.id','payments.currency_type_id');
        
        $payment_sale_notes = Payment::where('payments.total', '>', 0)
            ->select('payments.id', 'sale_notes.series', 'sale_notes.number', 'payments.currency_type_id', 'payments.total', 'cat_payment_methods.description AS payment_method', 
            'accounts.name AS account', 'symbol', 'persons.name AS customer', 'payments.created_at', 'payments.pos_id')
            ->join('sale_notes', 'sale_notes.id', 'payments.sale_note_id')
            ->join('persons', 'persons.id', 'payments.customer_id')
            ->join('cat_payment_methods', 'cat_payment_methods.id', 'payments.payment_method_id')
            ->join('accounts', 'accounts.id', 'payments.account_id')
            ->join('cat_currency_types', 'cat_currency_types.id', 'payments.currency_type_id');

        $records = $payment_documents->union($payment_sale_notes)->orderby('created_at', 'desc');
                
        return new PaymentCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function totals(Request $request)
    {
        $totalPEN1 = $this->query_total('documents', 'document_id', 'PEN');
        $totalPEN2 = $this->query_total('sale_notes', 'sale_note_id', 'PEN');

        $totalUSD1 = $this->query_total('documents', 'document_id', 'USD');
        $totalUSD2 = $this->query_total('sale_notes', 'sale_note_id', 'USD');

        $totalPEN = [
            'quantity' => $totalPEN1->quantity + $totalPEN2->quantity,
            'total' => is_null($totalPEN1->total) && is_null($totalPEN2->total) ? '0.00': ($totalPEN1->total + $totalPEN2->total)
        ];

        $totalUSD = [
            'quantity' => $totalUSD1->quantity + $totalUSD2->quantity,
            'total' => is_null($totalUSD1->total) && is_null($totalUSD2->total) ? '0.00': ($totalUSD1->total + $totalUSD2->total)
        ];
        
        $data = [
            'totalPEN' => $totalPEN,
            'totalUSD' => $totalUSD
        ];

        return compact('data');
    }

    public function query_total($table, $column, $currency='PEN')
    {
        $total = DB::connection('tenant')
                        ->table('payments')
                        ->select(DB::raw('COUNT(*) as quantity'), DB::raw('SUM(payments.total) as total'))
                        ->join($table, 'payments.'.$column, $table.'.id')
                        ->where('payments.currency_type_id', $currency)
                        ->where('payments.total', '>', 0)
                        ->first();
        
        return $total;
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

    public function store(PaymentRequest $request)
    {
        //$document_id = $request->input('document_id');
        $pos = Pos::active();

        if($pos == null)
        {
            return [
                'success' => false,
                'message' => "!Necesita aperturar una caja!"
            ];
        }
        else{
            $array = [$request, $pos];

            if($request->input('total') > $request->input('total_debt')) {
                return [
                    'success' => false,
                    'message' => 'El valor recibido no debe ser mayor a la deuda'
                ];
            }

            if($request->input('total') > 0)
            {
                $fact = DB::connection('tenant')->transaction(function () use ($array){

                    $request = $array[0];
                    $pos_id = $array[1];
    
                    if(is_null($request->input('document_id')))
                    {
                        $document = SaleNote::find($request->input('sale_note_id'));
                        $document->total_paid += $request->input('total');
                        $customer_id = $document->customer_id;
                        $document->save();
                    }
                    else
                    {
                        $document = Document::find($request->input('document_id'));
                        $document->total_paid += $request->input('total');
                        $customer_id = $document->customer_id;
                        $document->save();
                    }                
    
                    $payment = new Payment();
                    $payment->customer_id = $customer_id;
                    $payment->pos_id = $pos_id;
    
                    if(is_null($request->input('description')))
                    {
                        $payment->description = '';
                    }
    
                    $payment->fill($request->all());
                    $payment->save();
    
                    $account = Account::find($request->input('account_id'));
                    $account->current_balance += $request->input('total');
                    $account->save();
    
                    //inicio de caja
                    $pos = Pos::find($pos_id);
                    $pos->id = $pos_id;
                    $pos->close_amount += $request->input('total');
                    $pos->save();
    
                    //fin de caja
    
                    return $payment;
                });
            }
            
            return [
                'success' => true,
                'message' => 'Pago registrado con éxito'
            ];
        }
    }

    public function destroy($id)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($id){
            $payment = Payment::findOrFail($id);
            $document_id = $payment->document_id;
            $account_id = $payment->account_id;
            $sale_note_id = $payment->sale_note_id;
            $pos_id = $payment->pos_id;
            $total = $payment->total;
            $payment->delete();

            if(is_null($document_id))
            {   
                $document = SaleNote::find($sale_note_id);
                $document->total_paid -= $total;
                $document->save();
            }
            else if(is_null($sale_note_id))
            {
                $document = Document::find($document_id);
                $document->total_paid -= $total;
                $document->save();
            }
            
            $pos = Pos::find($pos_id);
            $pos->close_amount -= $total;
            $pos->save();

            $account = Account::find($account_id);
            $account->current_balance -= $total;
            $account->save();
        });
        
        return [
            'success' => true,
            'message' => 'Pago eliminado con éxito'
        ];
    }
}