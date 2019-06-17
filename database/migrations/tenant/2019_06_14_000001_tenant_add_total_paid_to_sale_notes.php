<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTotalPaidToSaleNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->decimal('total_paid', 12, 2)->default(0)->after('total');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->dropColumn('total_paid');
        });
    }
}
