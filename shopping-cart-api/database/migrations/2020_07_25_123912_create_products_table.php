<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name' )->unique();
            $table->string('slug' )->unique();
            $table->string('description' )->nullable();
            $table->string('image' )->nullable();
            $table->double('price' );
            $table->tinyInteger('active')->default(true )->unsigned();
            $table->timestamp('created_at')->default( DB::raw( 'CURRENT_TIMESTAMP' ) );
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
