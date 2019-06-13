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
            $table->unsignedInteger('document_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('payment_method_id');
            $table->date('date_of_issue');
            $table->string('currency_type_id',3);
            $table->unsignedInteger('account_id');
            $table->string('description')->nullable();
            $table->decimal('total', 12, 2);
            $table->timestamps();
            
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
            $table->foreign('payment_method_id')->references('id')->on('cat_payment_methods');
            $table->foreign('account_id')->references('id')->on('accounts');
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
