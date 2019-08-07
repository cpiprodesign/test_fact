<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataInModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('modules')->insert([
            ['value' => 'alerts', 'description' => 'Alertas'],
            ['value' => 'price-list', 'description' => 'Lista de precios'],
            ['value' => 'expenses', 'description' => 'Gastos'],
            ['value' => 'payments', 'description' => 'Pagos'],
            ['value' => 'accounts', 'description' => 'Bancos'],
            ['value' => 'credit-notes', 'description' => 'Notas de crédito'],
            ['value' => 'quotations', 'description' => 'Cotizaciones'],
            ['value' => 'roles', 'description' => 'Roles'],
            ['value' => 'inventory', 'description' => 'Inventario'],
            ['value' => 'warehouses', 'description' => 'Almacén'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}