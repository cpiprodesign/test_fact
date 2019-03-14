<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos', function (Blueprint $table) {
            $table->increments('id');

            // unity
            $table->integer('user_id')->unsigned();
            $table->integer('establishment_id')->unsigned();
//            $table->char('name', 50);

            // data

            $table->float('cash_limit', 15, 4)->default(2000);
            $table->float('open_amount', 15, 4);
            $table->float('close_amount', 15, 4)->default(0)->nullable();
            $table->integer('sales_count')->default(0);
            $table->enum('status', ['open', 'suspended', 'blocked', 'close'])->default('open');

            // security
//            $table->ipAddress('ip');
//            $table->uuid('hash');

            // log
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('establishment_id')->references('id')->on('establishments');
        });

    //     DB::table('pos')->insert([
    //         ['user_id' => 1, 'establishment_id' => 1, 'open_amount' => 500, 'ip' => '162.74.135.1']
    //     ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos');
    }
}
