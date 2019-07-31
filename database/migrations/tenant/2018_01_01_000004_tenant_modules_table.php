<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->string('description');
            $table->timestamps();
        });

        DB::table('modules')->insert([
            ['value' => 'documents', 'description' => 'Ventas'],
            ['value' => 'items', 'description' => 'Productos'],
            ['value' => 'customers', 'description' => 'Clientes'],
            ['value' => 'suppliers', 'description' => 'Proveedores'],
            ['value' => 'purchases', 'description' => 'Compras'],
            ['value' => 'summaries', 'description' => 'Resúmenes'],
            ['value' => 'voided', 'description' => 'Anulaciones'],
            ['value' => 'companies', 'description' => 'Empresa'],
            ['value' => 'retentions', 'description' => 'Retenciones'],
            ['value' => 'dispatches', 'description' => 'Guías de remisión'],
            ['value' => 'users', 'description' => 'Usuarios'],
            ['value' => 'establishments', 'description' => 'Establecimientos'],
            ['value' => 'catalogs', 'description' => 'Catálogos'],
            ['value' => 'advanced', 'description' => 'Documentos Avanzados'],
            ['value' => 'reports', 'description' => 'Reportes'],
            ['value' => 'configuration', 'description' => 'Configuration'],
            ['value' => 'alerts', 'description' => 'alerts'],
            ['value' => 'certificates', 'description' => 'certificates'],
            ['value' => 'bank_accounts', 'description' => 'bank_accounts'],
            ['value' => 'series', 'description' => 'series'],
            ['value' => 'charge_discounts', 'description' => 'charge_discounts'],
            ['value' => 'price-list', 'description' => 'price-list'],
            ['value' => 'expenses', 'description' => 'expenses'],
            ['value' => 'payments', 'description' => 'payments'],
            ['value' => 'accounts', 'description' => 'accounts'],
            ['value' => 'persons', 'description' => 'persons'],
            ['value' => 'pos', 'description' => 'pos'],
            ['value' => 'credit-notes', 'description' => 'credit-notes'],
            // ['value' => 'box', 'description' => 'box'],
            ['value' => 'quotations', 'description' => 'quotations'],
            ['value' => 'sale-notes', 'description' => 'sale-notes'],
            ['value' => 'options', 'description' => 'options'],
            // ['value' => 'services', 'description' => 'services'],
            // ['value' => 'codes', 'description' => 'codes'],
            // ['value' => 'unit_types', 'description' => 'unit_types'],
            // ['value' => 'banks', 'description' => 'banks'],
            // ['value' => 'exchange_rates', 'description' => 'exchange_rates'],
            // ['value' => 'currency_types', 'description' => 'currency_types'],
            ['value' => 'perceptions', 'description' => 'perceptions'],
            // ['value' => 'tribute_concept_types', 'description' => 'tribute_concept_types'],
            // ['value' => 'trademarks', 'description' => 'trademarks'],
            // ['value' => 'item_category', 'description' => 'item_category'],
            ['value' => 'roles', 'description' => 'roles'],
            // ['value' => 'permisos', 'description' => 'permisos'],
            ['value' => 'inventory', 'description' => 'inventory']
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