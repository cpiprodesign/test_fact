<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantItemCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('item_category', function (Blueprint $table) {

            $table->increments('id');

            // data
            $table->char('description', 100)->unique()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();

            // log
            $table->timestamps();
            $table->softDeletes();

            // relación circular
            $table->foreign('parent_id')->references('id')->on('item_category');
        });

        // campo en tabla
        Schema::table('items', function (Blueprint $table) {

            $table->integer('item_category_id')->unsigned()->nullable();
            $table->foreign('item_category_id')->references('id')->on('item_category');
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

        Schema::dropIfExists('item_category');

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['item_category_id']);
            $table->dropColumn('item_category_id');
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
