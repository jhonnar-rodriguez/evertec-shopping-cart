<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        $this->call(UsersTableSeeder::class );
        $this->call( BusinessTableSeeder::class );
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        Model::reguard();
    }
}
