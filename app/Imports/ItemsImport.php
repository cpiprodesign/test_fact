<?php

namespace App\Imports;

use App\Models\Tenant\Item;
use App\Models\Tenant\Warehouse;
use App\Models\Tenant\ItemWarehouse;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ItemsImport implements ToCollection
{
    use Importable;

    protected $data;

    public function collection(Collection $rows)
    {
        $total = count($rows);
        $registered = 0;
        unset($rows[0]);

        foreach ($rows as $row)
        {
            if($row[0] != "")
            {
                $description = $row[0];
                $item_type_id = '01';
                $internal_id = ($row[1])?:null;
                $item_code = ($row[2])?:null;
                $unit_type_id = $row[3];
                $currency_type_id = $row[4];
                $sale_unit_price = $row[5];
                $sale_affectation_igv_type_id = $row[6];
                $purchase_unit_price = ($row[7])?:0;
                $purchase_affectation_igv_type_id = ($row[8])?:null;
                $stock = $row[9];
                $stock_min = $row[10];

                $register = true;
                
                $item = Item::where('description', $description)->exists();
                
                if($item)
                {
                    $register = false;
                }
                else
                {
                    if($internal_id)
                    {
                        $item = Item::where('internal_id', $internal_id)->exists();

                        if($item)
                        {
                            $register = false;
                        }
                        else
                        {
                            $register = true;
                        }
                    }
                    else
                    {
                        $register = true;
                    }
                }

                if($register) {
                    $register = Item::create([
                        'description' => $description,
                        'item_type_id' => $item_type_id,
                        'internal_id' => $internal_id,
                        'item_code' => $item_code,
                        'unit_type_id' => $unit_type_id,
                        'currency_type_id' => $currency_type_id,
                        'sale_unit_price' => $sale_unit_price,
                        'sale_affectation_igv_type_id' => $sale_affectation_igv_type_id,
                        'purchase_unit_price' => $purchase_unit_price,
                        'purchase_affectation_igv_type_id' => $purchase_affectation_igv_type_id,
                        //'stock' => $stock,
                        'stock_min' => $stock_min,
                    ]);
                    
                    $array_stock = explode(";", $stock);

                    $warehouses = Warehouse::all();

                    foreach($warehouses as $key => $warehouse)
                    {
                        if(empty($array_stock[$key]))
                        {
                            $stock = 0;
                        }
                        else
                        {
                            $stock = $array_stock[$key];
                        }

                        $item_warehouse = new ItemWarehouse();
                        $item_warehouse->item_id = $register->id;
                        $item_warehouse->warehouse_id = $warehouse->id;
                        $item_warehouse->stock = $stock;
                        $item_warehouse->save();
                    }

                    $registered += 1;
                }
            }
        }

        $this->data = compact('total', 'registered');
    }

    public function getData()
    {
        return $this->data;
    }
}
