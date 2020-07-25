<?php

use App\Models\Business\Product\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $productsTable = config( 'business.core.products.table' );

        DB::table( $productsTable )->truncate();

        factory( Product::class, 15 )->create();
    }
}
