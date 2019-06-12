<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('payment_method_type_id');
            $table->string('currency_type_id',3);
            $table->integer('account_id');
            $table->string('description')->nullable();
            $table->decimal('total', 12, 4);
            $table->timestamps();
            
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
