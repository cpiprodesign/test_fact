<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Trademarks;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrademarksController extends Controller
{


    public function records(Request $request)
    {
        $records = Trademarks::all();

        return ['data' => $records];

        //return new PosCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function record($id)
    {
        $record = Trademarks::select(['id', 'name'])->findOrFail($id)->toArray();

        return ['data' => $record];
    }


    public function store(Request $request)
    {

        try {

            $id = $request->input('id');
            $trademark = Trademarks::firstOrNew(['id' => $id]);
            $trademark->fill($request->all());
            $trademark->save();

            return [
                'success' => true,
                'message' => ($id) ? 'Marca editada con éxito' : 'Marca registrada con éxito'
            ];

        } catch (QueryException $e) {

            return [
                'success' => false,
                'message' => 'Marca ya existente en el registro'//$e->getMessage()
            ];

        }


    }

    public function destroy($id)
    {
        $record = Trademarks::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Atributo eliminado con éxito'
        ];
    }
}
