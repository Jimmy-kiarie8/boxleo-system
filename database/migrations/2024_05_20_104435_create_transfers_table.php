<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('from_warehouse_id'); // Foreign key to the warehouses table
            $table->unsignedBigInteger('to_warehouse_id')->nullable(); // Foreign key to the warehouses table
            $table->unsignedBigInteger('approved_by')->nullable(); // Foreign key to users table (if applicable)
            $table->integer('quantity'); // Quantity of the product being transferred
            $table->string('transfer_type')->nullable();
            $table->integer('received')->nullable();
            $table->integer('faulty')->nullable();
            $table->string('status')->default('pending');

            // Adding foreign key constraints
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
};
