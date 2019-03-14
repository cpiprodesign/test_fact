<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPosSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_sales_details', function (Blueprint $table) {
            $table->increments('id');

            // unity
            $table->integer('pos_sales_id')->unsigned();

            // data
            $table->char('type', 20);
            $table->float('amount', 15, 4);
            $table->char('reference')->nullable();

            // log
            $table->timestamps();
            $table->softDeletes();

            // link
            $table->foreign('pos_sales_id')->references('id')->on('pos_sales');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_sales_details');
    }
}
