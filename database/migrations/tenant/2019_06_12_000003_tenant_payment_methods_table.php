<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active');
            $table->string('description');
        });

        DB::table('cat_payment_methods')->insert([
            ['active' => true, 'description' => 'Efectivo'],
            ['active' => true, 'description' => 'Consignación'],
            ['active' => true, 'description' => 'Transferencia'],
            ['active' => true, 'description' => 'Cheque'],
            ['active' => true, 'description' => 'Tarjeta de crédito'],
            ['active' => true, 'description' => 'Tarjeta de débito'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_payment_methods');
    }
}
