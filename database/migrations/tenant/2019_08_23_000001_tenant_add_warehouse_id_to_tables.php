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
        
        DB::statement("UPDATE documents SET establishment_id = warehouse_id");
        DB::statement("UPDATE purchases SET establishment_id = warehouse_id");
        DB::statement("UPDATE sale_notes SET establishment_id = warehouse_id");

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });

        Schema::table('sale_notes', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });
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
