<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table)
        {
            $table->unsignedInteger('cart_id' );
            $table->unsignedInteger('product_id' );
            $table->unsignedInteger('quantity' );
            $table->timestamp('created_at' )->default( DB::raw( 'CURRENT_TIMESTAMP' ) );
            $table->timestamp('updated_at' )->nullable();

            # Foreign Keys
            $table->foreign('cart_id' )
                ->references('id' )
                ->on( config( 'business.core.carts.table' ) )
                ->onDelete('cascade');

            $table->foreign('product_id' )
                ->references('id' )
                ->on( config( 'business.core.products.table' ) )
                ->onDelete('cascade');

            # Table primary keys
            $table->primary([
                'cart_id',
                'product_id'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
