<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Establishment;
use Illuminate\Http\Request;
use Modules\Inventory\Http\Resources\WarehouseCollection;
use Modules\Inventory\Http\Resources\WarehouseResource;
use Modules\Inventory\Http\Requests\WarehouseRequest;
use Modules\Inventory\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        return view('inventory::warehouses.index');
    }

    public function columns()
    {
        return [
            'description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        $records = Warehouse::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('description');

        return new WarehouseCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        $establishments = Establishment::get();

        return compact('establishments');
    }
    
    public function totals(Request $request)
    {
    }

    public function record($id)
    {
        $record = new WarehouseResource(Warehouse::findOrFail($id));

        return $record;
    }

    public function store(WarehouseRequest $request)
    {
        $id = $request->input('id');
        $record = Warehouse::firstOrNew(['id' => $id]);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Almacén editado con éxito':'Almacén registrado con éxito',
            'id' => $record->id
        ];
    }
}