<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeDecimalPriceItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('sale_unit_price', 12, 4)->change();
            $table->decimal('purchase_unit_price', 12, 4)->change();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('items', function (Blueprint $table) {        
        });
    }
}
