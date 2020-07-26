<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table)
        {
            $table->id();
            $table->unsignedInteger('user_id' )->nullable();
            $table->timestamp('created_at' )->default( DB::raw( 'CURRENT_TIMESTAMP' ) );
            $table->timestamp('updated_at' )->nullable();
            $table->primary('id' );

            $table->foreign('user_id' )
                ->references('id' )
                ->on(config( 'business.access.users.table' ) )
                ->onDelete('cascade' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
