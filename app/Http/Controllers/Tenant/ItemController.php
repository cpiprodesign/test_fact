<?php

namespace App\Http\Controllers\Tenant;

use App\Imports\ItemsImport;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use App\Models\Tenant\Item;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Warehouse;
use App\Models\Tenant\EstablishmentItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ItemRequest;
use App\Http\Resources\Tenant\ItemCollection;
use App\Http\Resources\Tenant\ItemResource;
use App\Models\Tenant\ItemCategory;
use App\Models\Tenant\Trademarks;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        return view('tenant.items.index');
    }

    public function columns()
    {
        return [
            'description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        $records = Item::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('description');

        return new ItemCollection($records->paginate(env('ITEMS_PER_PAGE', 10)));
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $unit_types = UnitType::whereActive()->orderByDescription()->get();
        $currency_types = CurrencyType::whereActive()->orderByDescription()->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();
        $system_isc_types = SystemIscType::whereActive()->orderByDescription()->get();
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $warehouses = Warehouse::all();

        $trademarks = Trademarks::orderBy('name')->get();
        $item_category = ItemCategory::WhereNull('parent_id')->orderBy('description')->get();

        return compact('unit_types', 'currency_types', 'attribute_types', 'system_isc_types', 'affectation_igv_types', 'trademarks', 'item_category', 'warehouses');
    }

    public function record($id)
    {
        $record = new ItemResource(Item::findOrFail($id));

        return $record;
    }

    public function stock_details($id)
    {
        $sql = "SELECT itw.stock, ite.`stock_min`,
                (SELECT description FROM warehouses war WHERE war.id = itw.warehouse_id LIMIT 1) AS almacen
                FROM items ite
                INNER JOIN item_warehouse itw ON itw.item_id = ite.id
                WHERE itw.item_id = ?";

        $stock_details = DB::connection('tenant')->select($sql, array($id));

        return compact('stock_details');
    }

//    No Funcional
//    public function stocks($id)
//    {
//
//        $stocks = EstablishmentItem::where('item_id', $id)->get();
//
//        return $stocks;
//    }

    public function store(ItemRequest $request)
    {
        $id = $request->input('id');
        $item = Item::firstOrNew(['id' => $id]);
        $item->item_type_id = '01';
        $item->fill($request->all());
        $item->save();

        // stock actual
        foreach ($request->item_warehouse as $stock_by_location) {
            $stock = $item->item_warehouse()->firstOrNew(['warehouse_id' => $stock_by_location['warehouse_id']]);
            $stock->stock = $stock_by_location['quantity'];
            $stock->save();
        }

        return [
            'success' => true,
            'message' => ($id) ? 'Producto editado con éxito' : 'Producto registrado con éxito',
            'id' => $item->id
        ];
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Producto eliminado con éxito'
        ];
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $import = new ItemsImport();
                $import->import($request->file('file'), null, Excel::XLSX);
                $data = $import->getData();
                return [
                    'success' => true,
                    'message' => __('app.actions.upload.success'),
                    'data' => $data
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' => __('app.actions.upload.error'),
        ];
    }
}
