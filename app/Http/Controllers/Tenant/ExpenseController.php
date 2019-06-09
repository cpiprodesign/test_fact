<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Expense;
use App\Models\Tenant\Establishment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ExpenseRequest;
use App\Http\Resources\Tenant\ExpenseCollection;
use App\Http\Resources\Tenant\ExpenseResource;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('tenant.expenses.index');
    }

    public function columns()
    {
        return [
            'description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        $records = Expense::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('date_of_issue');

        return new ExpenseCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $currency_types = CurrencyType::whereActive()->orderByDescription()->get();

        return compact('currency_types');
    }

    public function record($id)
    {
        $record = new ExpenseResource(Expense::findOrFail($id));

        return $record;
    }

    public function store(ExpenseRequest $request)
    {
        // dd($request->input());
        $id = $request->input('id');
        $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();

        $expense = Expense::firstOrNew(['id' => $id]);

        if($request->has_voucher)
        {
            $detail_voucher = [
                'company_number' => $request->detail_voucher['company_number'],
                'company_name' => $request->detail_voucher['company_name'],
                'document_type' => $request->detail_voucher['document_type'],
                'document_number' => $request->detail_voucher['document_number']
            ];

            $detail_voucher = json_encode($detail_voucher);
            $expense->detail_voucher  = $detail_voucher;

            //$detail_voucher = null;
        }

        // $detail_voucher = [
        //     'company_number' => $request->detail_voucher['company_number'],
        //     'company_name' => $request->detail_voucher['company_name'],
        //     'document_type' => $request->detail_voucher['document_type'],
        //     'document_number' => $request->detail_voucher['document_number']
        // ];

        // $detail_voucher = json_encode($detail_voucher);
        // $expense->detail_voucher  = $detail_voucher;
        
        
        $expense->user_id = auth()->id();
        $expense->establishment_id = $establishment->id;
        $expense->fill($request->all());
        $expense->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Gasto editado con éxito' : 'Gasto registrado con éxito',
            'id' => $expense->id
        ];
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return [
            'success' => true,
            'message' => 'Gasto eliminado con éxito'
        ];
    }
}
