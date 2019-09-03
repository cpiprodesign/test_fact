<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddWarehouseIdToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_id')->after('establishment_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_id')->after('establishment_id');
        });

        Schema::table('sale_notes', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_id')->after('establishment_id');
        });
        
        DB::statement("UPDATE documents SET warehouse_id = establishment_id");
        DB::statement("UPDATE purchases SET warehouse_id = establishment_id");
        DB::statement("UPDATE sale_notes SET warehouse_id = establishment_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('warehouse_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('warehouse_id');
        });

        Schema::table('sale-notes', function (Blueprint $table) {
            $table->dropColumn('warehouse_id');
        });
    }
}
