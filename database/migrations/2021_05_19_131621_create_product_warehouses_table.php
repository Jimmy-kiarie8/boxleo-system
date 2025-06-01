<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('row_id');
            $table->unsignedBigInteger('bay');
            $table->unsignedBigInteger('level_id');
            $table->integer('quantity')->nullable();
            // $table->unsignedBigInteger('seller_id')->nullable();

            // $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); 
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade'); 
            $table->foreign('bay')->references('id')->on('bays')->onDelete('cascade'); 
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade'); 
            $table->foreign('row_id')->references('id')->on('rows')->onDelete('cascade'); 
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
        Schema::dropIfExists('product_warehouses');
    }
}
