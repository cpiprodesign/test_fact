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
        
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Administrador', 'slug' => 'administrador', 'description' => 'Administrador del sistema', 'created_at' =>DB::raw ('NOW()'), 'updated_at' => DB::raw('NOW()'), 'special' => 'all-access'],
            ['id' => 2, 'name' => 'Vendedor', 'slug' => 'vendedor', 'description' => 'Vendedor del sistema', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()'), 'special' => DB::raw('NULL')],
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
