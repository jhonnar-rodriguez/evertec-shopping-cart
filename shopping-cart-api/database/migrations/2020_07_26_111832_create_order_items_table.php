<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function ( Blueprint $table )
        {
            $table->unsignedInteger('order_id' );
            $table->unsignedInteger('product_id' );
            $table->unsignedInteger('quantity' );
            $table->timestamp('created_at' )->default( DB::raw( 'CURRENT_TIMESTAMP' ) );
            $table->timestamp('updated_at' )->nullable();

            # Foreign Keys
            $table->foreign('order_id' )
                ->references('id' )
                ->on( config( 'business.core.orders.table' ) )
                ->onDelete('cascade');

            $table->foreign('product_id' )
                ->references('id' )
                ->on( config( 'business.core.products.table' ) )
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items' );
    }
}
