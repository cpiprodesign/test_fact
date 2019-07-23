<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddSpecialRoleColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name = config('shinobi.tables.roles');

        Schema::table($name, function ($table) {
            $table->enum('special', ['all-access', 'no-access'])->nullable();
        });

        
        //CREAR ROLES
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Administrador', 'slug' => 'administrador', 'description' => 'Administrador del sistema', 'created_at' =>DB::raw ('NOW()'), 'updated_at' => DB::raw('NOW()'), 'special' => 'all-access'],
            ['id' => 2, 'name' => 'Vendedor', 'slug' => 'vendedor', 'description' => 'Vendedor del sistema', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()'), 'special' => DB::raw('NULL')],
            ['id' => 3, 'name' => 'Cajero', 'slug' => 'cajero', 'description' => 'Cajero del negocio', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()'), 'special' => 'no-access'],
            ['id' => 4, 'name' => 'Gestion Bancos', 'slug' => 'gestion.banco', 'description' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()'), 'special' => DB::raw('NULL')],
            ['id' => 5, 'name' => 'Reportes', 'slug' => 'reportes', 'description' => '', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()'), 'special' => DB::raw('NULL')],
        ]);

        DB::table('permission_role')->insert([
            ['id' => 1, 'permission_id' => 7, 'role_id' => 5, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 2, 'permission_id' => 1, 'role_id' => 4, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 3, 'permission_id' => 2, 'role_id' => 4, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 4, 'permission_id' => 3, 'role_id' => 4, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 5, 'permission_id' => 4, 'role_id' => 4, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 6, 'permission_id' => 5, 'role_id' => 4, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 7, 'permission_id' => 6, 'role_id' => 4, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $name = config('shinobi.tables.roles');

        Schema::table($name, function ($table) {
            $table->dropColumn('special');
        });
    }
}
