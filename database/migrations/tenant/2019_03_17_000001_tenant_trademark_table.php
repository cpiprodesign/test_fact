<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantTrademarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // tabla de marcas
        Schema::create('trademarks', function (Blueprint $table) {

            $table->increments('id');

            // data
            $table->char('name', 100)->unique()->nullable();

            // log
            $table->timestamps();
            $table->softDeletes();
        });

        // campo en tabla
        Schema::table('items', function (Blueprint $table) {

            $table->integer('trademark_id')->unsigned()->nullable();
            $table->foreign('trademark_id')->references('id')->on('trademarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('trademarks');

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['trademark_id']);
            $table->dropColumn('trademark_id');
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
