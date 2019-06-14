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
use App\Http\Resources\Tenant\PaymentResource;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        return view('tenant.payments.index');
    }

    public function columns()
    {
        return [
            'description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        $records = Payment::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('date_of_issue');

        return new PaymentCollection(Payment::paginate(env('ITEMS_PER_PAGE', 10)));
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

        $array = [$request];

        if($request->input('total') > $request->input('total_debt')) {
            return [
                'success' => false,
                'message' => 'El valor recibido no debe ser mayor a la deuda'
            ];
        }

        $fact = DB::connection('tenant')->transaction(function () use ($request){

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
            $payment->fill($request->all());
            $payment->save();

            $account = Account::find($request->input('account_id'));
            $account->current_balance += $request->input('total');
            $account->save();

            return $payment;
        });
        
        return [
            'success' => true,
            'message' => 'Pago registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($id){
            $payment = Payment::findOrFail($id);
            $document_id = $payment->document_id;
            $account_id = $payment->account_id;
            $total = $payment->total;
            $payment->delete();

            $document = Document::find($document_id);
            $document->total_paid -= $total;
            $document->save();

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
