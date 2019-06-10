<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('establishment_id');
            $table->boolean('has_voucher')->default(false);
            $table->date('date_of_issue');
            $table->string('description', 500);
            $table->string('currency_type_id', 4);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('detail')->nullable();
            $table->string('company_number', 15)->nullable();
            $table->json('detail_voucher')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');            
            $table->foreign('establishment_id')->references('id')->on('establishments');
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
        Schema::dropIfExists('expenses');
    }
}
