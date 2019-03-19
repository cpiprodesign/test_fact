<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\ItemCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemCategoryController extends Controller
{

    function tables()
    {
        $parents = ItemCategory::WhereNull('parent_id')->get();
        return compact('parents');
    }

    public function records(Request $request)
    {
        $records = ItemCategory::WhereNull('parent_id')->get();

        return ['data' => $records];

        //return new PosCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function record($id)
    {
        $record = ItemCategory::findOrFail($id)->toArray();

        return ['data' => $record];
    }


    public function store(Request $request)
    {

        try {

            $id = $request->input('id');
            $trademark = ItemCategory::firstOrNew(['id' => $id]);
            $trademark->fill($request->all());
            $trademark->save();

            return [
                'success' => true,
                'message' => ($id) ? 'Marca editada con éxito' : 'Marca registrada con éxito'
            ];

        } catch (QueryException $e) {

            return [
                'success' => false,
//                'message' => 'Marca ya existente en el registro'//$e->getMessage()
                'message' => $e->getMessage()
            ];

        }


    }

    public function destroy($id)
    {
        $record = ItemCategory::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Atributo eliminado con éxito'
        ];
    }
}
