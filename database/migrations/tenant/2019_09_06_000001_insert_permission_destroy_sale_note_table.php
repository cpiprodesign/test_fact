<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class InsertPermissionDestroySaleNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            ['name' => 'Eliminar notas de venta', 'slug' => 'tenant.sale-notes.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
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