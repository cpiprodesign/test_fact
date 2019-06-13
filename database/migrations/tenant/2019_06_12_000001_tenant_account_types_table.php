<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_account_types', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active');
            $table->string('description');
        });

        DB::table('cat_account_types')->insert([
            ['active' => true, 'description' => 'Banco'],
            ['active' => true, 'description' => 'Tarjeta de crédito'],
            ['active' => true, 'description' => 'Efectivo'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_account_types');
    }
}
