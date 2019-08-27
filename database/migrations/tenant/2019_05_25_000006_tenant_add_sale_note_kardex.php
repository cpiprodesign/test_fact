<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TenantAddSaleNoteKardex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('kardex', function (Blueprint $table) {

            $table->unsignedInteger('sale_note_id')->nullable();
 
            $table->foreign('sale_note_id')->references('id')->on('sale_notes')->onDelete('cascade');

            DB::statement("ALTER TABLE kardex MODIFY COLUMN type ENUM('sale', 'purchase', 'sale-note')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    
    public function down()
    {
        Schema::dropIfExists('kardex');
    }
}
