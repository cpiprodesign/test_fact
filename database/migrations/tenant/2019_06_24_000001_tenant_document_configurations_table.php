<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantDocumentConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('seller')->default(false);
            $table->timestamps();
        });

        DB::table('document_configurations')->insert([
            ['seller' => false, 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_configurations');        
    }
}
