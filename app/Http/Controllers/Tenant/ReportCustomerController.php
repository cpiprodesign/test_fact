<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Expense;
use App\Models\Tenant\Person;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ExpenseRequest;
use App\Http\Resources\Tenant\ReportCustomerCollection;
use App\Http\Resources\Tenant\DocumentCollection;
use App\Http\Resources\Tenant\ExpenseResource;
use Exception;
use Illuminate\Http\Request;
use App\Models\Tenant\Document;
use Illuminate\Support\Facades\DB;

class ReportCustomerController extends Controller
{
    public function index()
    {
        return view('tenant.reports.customers.index');
    }

    public function detail(Person $person)
    {
        $totals = DB::connection('tenant')->table('persons as per')
        ->select(DB::raw('(SELECT SUM(total) FROM documents AS doc WHERE doc.customer_id = per.id) AS total'),
            DB::raw('(SELECT SUM(total) FROM sale_notes AS san WHERE san.customer_id = per.id) AS total2'),
            DB::raw('(SELECT SUM(total_paid) FROM documents AS doc WHERE doc.customer_id = per.id) AS total_paid'),
            DB::raw('(SELECT SUM(total_paid) FROM sale_notes AS doc WHERE doc.customer_id = per.id) AS total_paid2'))
        ->where('per.type', 'customers')
        ->where('per.id', $person->id)
        ->first();

        return view('tenant.reports.customers.detail', compact('person', 'totals'));
    }

    public function sells(Person $person, Request $request)
    {
        $records = Document::where($request->column, 'like', "%{$request->value}%")
            ->where('customer_id', $person->id);

        return new DocumentCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número'
        ];
    }

    public function sell_columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
        $records = DB::connection('tenant')->table('persons as per')
        ->select('per.id', 'idt.description as document_type', 'per.number', 'per.name',
            DB::raw('(SELECT SUM(total) FROM documents AS doc WHERE doc.customer_id = per.id) AS total'),
            DB::raw('(SELECT SUM(total) FROM sale_notes AS san WHERE san.customer_id = per.id) AS total2'),
            DB::raw('(SELECT SUM(total_paid) FROM documents AS doc WHERE doc.customer_id = per.id) AS total_paid'),
            DB::raw('(SELECT SUM(total_paid) FROM sale_notes AS doc WHERE doc.customer_id = per.id) AS total_paid2'),
            DB::raw('(SELECT COUNT(*) FROM documents AS doc WHERE doc.customer_id = per.id) AS quantity'),
            DB::raw('(SELECT COUNT(*) FROM sale_notes AS doc WHERE doc.customer_id = per.id) AS quantity2'))
        ->join('cat_identity_document_types as idt', 'idt.id', '=', 'per.identity_document_type_id')
        ->where('per.type', 'customers')
        ->where($request->column, 'like', "%{$request->value}%");
         
        return new ReportCustomerCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
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
}
