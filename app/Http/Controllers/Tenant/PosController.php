<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\PosCollection;
use App\Models\Tenant\Company;
use App\Models\Tenant\Pos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Integer;
use Barryvdh\DomPDF\Facade as PDF;

class PosController extends Controller
{
    //
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
        $records = Pos::orderBy('created_at', 'desc')->withTrashed();

        $request->column = explode('.', $request->column);

        $records->with([$request->column[0] => function ($q) use ($request) {
            $q->where($request->column[1], 'like', "%{$request->value}%");
        }]);


        return new PosCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function register()
    {
        if (is_null(Pos::active()))
            return redirect('/pos');

        return view('tenant.pos.register');
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
        $pos = Pos::withTrashed()->find($pos_id);

        return $pos;
    }

    public function operations($document_id, Request $request)
    {
        $pos_id = Pos::active();
        $pos = Pos::find($pos_id);

        $pos->close_amount += $request->balance['total'];
        $pos->save();

        $pos_sale = $pos->sales()->create([
            'document_id' => $document_id,
            'total' => $request->balance['total'],
            'payed' => $request->balance['pagando'],
            'delta' => $request->balance['delta'],
        ]);
        $pos_sale->save();

        foreach ($request->sale as $detail) {
            $pos_sale->details()->create([
                'type' => $detail['tipo'],
                'amount' => $detail['monto'],
                'reference' => isset($detail['ref']) ? $detail['ref'] : null,
            ])->save();
        }
    }

    public function pdf($pos_id) {
        $company = Company::first();
        $pos = $this->details($pos_id);

//        return view('tenant.reports.pos.report_pdf', compact(    "company", 'pos'));
        $pdf = PDF::loadView('tenant.reports.pos.report_pdf', compact("company", 'pos'));
        $filename = 'Reporte_Pos'.$pos->created_at->format('_Ymd_Hm');

        return $pdf->download($filename.'.pdf');
    }
}
