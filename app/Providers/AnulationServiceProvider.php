<?php

namespace App\Providers;

use App\Models\Tenant\Item; 
use App\Models\Tenant\Document;  
use App\Models\Tenant\Kardex; 
use Illuminate\Support\ServiceProvider;
use App\Traits\KardexTrait;

class AnulationServiceProvider extends ServiceProvider
{
    use KardexTrait;
     
    public function register()
    {
    }
    
    public function boot()
    {
        $this->anulation();
        
    }


    private function anulation(){

        Document::updated(function ($document) { 

            if($document['document_type_id'] == '01' || $document['document_type_id'] == '03'){

                if($document['state_type_id'] == 11){

                    foreach ($document['items'] as $detail) {     
    
                        $item = Item::find($detail['item_id']);
                        $item->stock = $item->stock + $detail['quantity'];
                        $item->save();                        
 
                        $this->saveKardex('sale', $item->id, $document['id'], -$detail['quantity']);
                         
                    }

                }
            }         

            
        });
        
    }
}
