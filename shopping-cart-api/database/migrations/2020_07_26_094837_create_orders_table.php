<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedInteger('user_id' );
            $table->unsignedInteger('cart_id' );
            $table->string( 'request_id' );
            $table->string( 'process_url' );
            $table->float( 'total' );
            $table->enum('status', ['CREATED', 'PAYED', 'REJECTED']);
            $table->timestamp('created_at' )->default( DB::raw( 'CURRENT_TIMESTAMP' ) );
            $table->timestamp('updated_at' )->nullable();

            # Foreign Keys
            $table->foreign('user_id' )
                ->references('id' )
                ->on( config( 'business.access.users.table' ) )
                ->onDelete('cascade');

            $table->foreign('cart_id' )
                ->references('id' )
                ->on( config( 'business.core.carts.table' ) )
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
        Schema::dropIfExists('orders');
    }
}
