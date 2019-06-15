<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\PriceList;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PriceListRequest;
use App\Http\Resources\Tenant\PriceListCollection;
use App\Http\Resources\Tenant\PriceListResource;
use Exception;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    public function index()
    {
        return view('tenant.price_list.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre'
        ];
    }

    public function records(Request $request)
    {
        $records = PriceList::where($request->column, 'like', "%{$request->value}%");

        return new PriceListCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function record($id)
    {
        $record = new PriceListResource(PriceList::findOrFail($id));

        return $record;
    }

    public function store(PriceListRequest $request)
    {
        $id = $request->input('id');
        $price_list = PriceList::firstOrNew(['id' => $id]);
        $price_list->fill($request->all());
        $price_list->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Lista de Precio editado con éxito' : 'Lista de Precio registrado con éxito',
            'id' => $price_list->id
        ];
    }

    public function destroy($id)
    {
        $expense = PriceList::findOrFail($id);
        $expense->delete();

        return [
            'success' => true,
            'message' => 'Lista de Precio eliminado con éxito'
        ];
    }
}
