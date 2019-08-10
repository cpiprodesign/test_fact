<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class TenantUpdatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->where('slug', 'tenant.documents.imprimir')->update(['name' => 'Imprimir', 'description' => 'Imprimir']);
        DB::table('permissions')->where('slug', 'tenant.documents.pos')->update(['name' => 'Punto de Venta', 'description' => 'Punto de Venta']);
        DB::table('modules')->where('value', 'pos')->update(['description' => 'Control de caja']);

        DB::table('permissions')->insert([
            ['name' => 'Agregar notas de crédito', 'slug' => 'tenant.credit-notes.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {

    }
}
