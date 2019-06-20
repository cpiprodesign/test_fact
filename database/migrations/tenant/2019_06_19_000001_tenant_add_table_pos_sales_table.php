<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTablePosSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_sales', function (Blueprint $table) {
            
            $table->string('table_name', 50)->default('documents')->after('document_id');

            $table->dropForeign(['document_id']);

            $table->float('total', 15, 4)->nullable()->change();
            $table->float('payed', 15, 4)->nullable()->change();
            $table->float('delta', 15, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('pos_sales');
    }
}