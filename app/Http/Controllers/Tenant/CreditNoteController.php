<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Document;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\DocumentCollection;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CreditNoteController extends Controller
{
    public function index()
    {
        return view('tenant.credit_notes.index');
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
        $records = Document::where($request->column, 'like', "%{$request->value}%")
            ->whereIn('document_type_id', ['07', '08'])
            ->latest();

        return new DocumentCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function totals(Request $request)
    {
        $total = DB::connection('tenant')
                        ->table('documents')
                        ->select(DB::raw('SUM(total) as total'))
                        ->where($request->column, 'like', "%{$request->value}%")
                        ->whereIn('document_type_id', ['07', '08'])
                        ->whereIn('state_type_id', ['01', '03', '05', '07'])
                        ->where('currency_type_id', 'PEN')
                        ->first();

        $total07 = DB::connection('tenant')
            ->table('documents')
            ->select(DB::raw('COUNT(*) as quantity'), DB::raw('SUM(total) as total'))
            ->where($request->column, 'like', "%{$request->value}%")
            ->whereIn('document_type_id', ['07'])
            ->where('currency_type_id', 'PEN')
            ->first();
        
        $total08 = DB::connection('tenant')
            ->table('documents')
            ->select(DB::raw('COUNT(*) as quantity'), DB::raw('SUM(total) as total'))
            ->where($request->column, 'like', "%{$request->value}%")
            ->whereIn('document_type_id', ['08'])
            ->where('currency_type_id', 'PEN')
            ->first();

        $total_state_types = DB::connection('tenant')
            ->table('documents AS doc')
            ->join('state_types AS stp', 'stp.id', 'doc.state_type_id')
            ->select(DB::raw('COUNT(*) AS quantity'), 'stp.description')
            ->where($request->column, 'like', "%{$request->value}%")
            ->whereIn('doc.document_type_id', ['08', '07'])
            ->groupBy('stp.description')
            ->get();
        
        $data = [
            'total' => $total, 
            'total07' => $total07, 
            'total08' => $total08,
            'total_state_types' => $total_state_types
        ];
        
        return compact('data');
    }
}
