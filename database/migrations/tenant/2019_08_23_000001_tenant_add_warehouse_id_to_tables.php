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
            $table->foreign('warehouse_id')->references('id')->on('establishments');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_id')->after('establishment_id');
            $table->foreign('warehouse_id')->references('id')->on('establishments');
        });

        Schema::table('sale-notes', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_id')->after('establishment_id');
            $table->foreign('warehouse_id')->references('id')->on('establishments');
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
    }
}
