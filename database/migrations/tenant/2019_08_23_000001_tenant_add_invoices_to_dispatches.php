<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddInvoicesToDispatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('dispatches', function (Blueprint $table) {
            $table->json('invoices')->nullable()->after('has_cdr');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('dispatches', function (Blueprint $table) {
            $table->dropColumn('invoices');
        });
    }
}
