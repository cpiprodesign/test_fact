<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPriceListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->enum('type', [1, 2]);
            $table->boolean('principal')->default(0);
            $table->boolean('active')->default(1);
            $table->decimal('value', 10, 2)->nullable();
            $table->timestamps();
        });

        DB::table('price_list')->insert([
            ['name' => 'General', 'type' => 2, 'principal' => true, 'active' => true, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list');
    }
}
