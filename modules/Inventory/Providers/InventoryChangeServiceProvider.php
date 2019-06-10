<?php

namespace Modules\Inventory\Providers;

use App\Models\Tenant\Item; 
use App\Models\Tenant\ItemWarehouse; 
use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Models\Inventory;
use Modules\Inventory\Traits\InventoryTrait;

class InventoryChangeServiceProvider extends ServiceProvider
{
    use InventoryTrait;
    
    public function register()
    {
    }

    public function boot()
    {
        $this->createdItemWarehouse();
        $this->inventory();
    }

    private function createdItemWarehouse()
    {
        ItemWarehouse::created(function ($item_warehouse) {
            // $warehouse = $this->findWarehouse();
            $this->createInitialInventory($item_warehouse->item_id, $item_warehouse->stock, $item_warehouse->warehouse_id);
        });
    }

    private function inventory()
    {
        Inventory::created(function ($inventory) {
            switch ($inventory->type) {
                case 1:
                    $this->createInventoryKardex($inventory, $inventory->item_id, $inventory->quantity, $inventory->warehouse_id);
                    //$this->updateStock($inventory->item_id, $inventory->quantity, $inventory->warehouse_id);
                    break;
                case 2:
                    //Origin
                    $this->createInventoryKardex($inventory, $inventory->item_id, -1 * $inventory->quantity, $inventory->warehouse_id);
                    $this->updateStock($inventory->item_id, -1 * $inventory->quantity, $inventory->warehouse_id);
                    //Arrival
                    $this->createInventoryKardex($inventory, $inventory->item_id, $inventory->quantity, $inventory->warehouse_destination_id);
                    $this->updateStock($inventory->item_id, $inventory->quantity, $inventory->warehouse_destination_id);
                    break;
                case 3:
                    $this->createInventoryKardex($inventory, $inventory->item_id, -1 * $inventory->quantity, $inventory->warehouse_id);
                    $this->updateStock($inventory->item_id, -1 * $inventory->quantity, $inventory->warehouse_id);
                    break;
                case 4:
                    $this->createInventoryKardex($inventory, $inventory->item_id, $inventory->quantity, $inventory->warehouse_id);
                    $this->updateStock($inventory->item_id, $inventory->quantity, $inventory->warehouse_id);
                    break;
                    
            }
        });
    }

}