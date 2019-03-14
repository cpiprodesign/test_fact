<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\PosCollection;
use App\Models\Tenant\Pos;
use App\Models\Tenant\PosSales;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Integer;

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
}
