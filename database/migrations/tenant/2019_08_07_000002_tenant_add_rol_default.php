<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class TenantAddRolDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admin = DB::select("SELECT EXISTS(SELECT * FROM users WHERE id = 1) AS a");

        if($admin[0]->a)
        {
            DB::table('role_user')->insert([
                ['role_id' => 1, 'user_id' => 1, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}