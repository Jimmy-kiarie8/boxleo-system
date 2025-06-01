<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSaleReturnSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sale_return_sale', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('Pending');
            $table->string('receive_status')->default('Pending');
            $table->string('refund_status')->default('Pending');
            $table->string('amount_refunded')->default(0);
            $table->string('remaining_refunded')->default(0);
            $table->integer('returned');
            $table->integer('tobe_returned');
            $table->text('comment')->nullable();

            $table->dateTime('return_date')->nullable();
            $table->dateTime('received_on')->nullable();

            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('product_sale_id');
            $table->unsignedBigInteger('return_sale_id');
            $table->unsignedBigInteger('sale_id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_sale_id')->references('id')->on('product_sale')->onDelete('cascade');
            $table->foreign('return_sale_id')->references('id')->on('return_sales')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('product_sale_return_sale');
    }
}
