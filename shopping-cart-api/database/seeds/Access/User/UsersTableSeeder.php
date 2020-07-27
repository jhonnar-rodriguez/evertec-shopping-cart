<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $usersTable = config( 'business.access.users.table' );

        DB::table( $usersTable )->truncate();

        DB::table( $usersTable )
            ->insert([
                'first_name' => 'Admin',
                'last_name' => 'Evertec',
                'email' => 'admin@evertect.com',
                'password' => bcrypt( 'password' ),
                'phone_number' => 31915464609,
                'active' => true,
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
            ]);
    }
}
