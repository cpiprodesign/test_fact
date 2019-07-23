<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name = config('shinobi.tables.permissions');

        Schema::create($name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });


        DB::table('permissions')->insert([
            ['id' => 1, 'name'=> 'Eliminar bancos', 'slug' => 'tenant.accounts.destroy', 'description' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 2, 'name'=> 'Crear banco', 'slug' => 'tenant.accounts.store', 'description' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 3, 'name'=> 'Editar bancos', 'slug' => 'tenant.accounts.update', 'description' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 4, 'name'=> 'Reporte bancos', 'slug' => 'tenant.accounts.report', 'description' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 5, 'name'=> 'Consultas bancos', 'slug' => 'tenant.accounts.record', 'description' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 6, 'name'=> 'Mostrar bancos', 'slug' => 'tenant.accounts.index', 'description' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 7, 'name'=> 'Mostrar reportes', 'slug' => 'tenant.reports.index', 'description' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
        ]);
        
        

    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        $name = config('shinobi.tables.permissions');

        Schema::drop($name);
    }
}
