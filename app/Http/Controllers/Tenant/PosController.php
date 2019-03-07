<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\PosCollection;
use App\Models\Tenant\Pos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PosController extends Controller
{
    //
    public function index()
    {
        return view('tenant.pos.index');
    }

    public function columns()
    {
        return [
            'ip' => 'Dirección IP',
            'establishment' => 'Ubicación'
        ];
    }

    public function records(Request $request)
    {
        $records = Pos::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('establishment_id');

        return new PosCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function register($uuid = null)
    {
        return view('tenant.pos.register');
    }
}
