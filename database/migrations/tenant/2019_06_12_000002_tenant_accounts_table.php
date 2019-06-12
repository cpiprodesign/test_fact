<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('number')->nullable();
            $table->string('currency_type_id',3);
            $table->unsignedInteger('account_type_id');
            $table->date('date_of_issue');
            $table->decimal('beginning_balance', 12, 2);
            $table->decimal('current_balance', 12, 2);
            $table->string('description')->nullable();
            $table->timestamps();
            
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
            $table->foreign('account_type_id')->references('id')->on('cat_account_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
