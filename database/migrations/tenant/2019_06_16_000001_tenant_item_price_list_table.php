<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantItemPriceListTable extends Migration
{
    public function up()
    {
        Schema::create('item_price_list', function (Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('price_list_id');
            $table->decimal('value', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_price_list');
    }
}
