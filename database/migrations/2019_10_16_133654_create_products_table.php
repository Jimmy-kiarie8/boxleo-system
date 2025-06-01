<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->bigIncrements('id');
            $table->string('product_name')->nullable();
            $table->string('sku_no')->nullable();
            $table->string('bar_code')->nullable();
            $table->boolean('active')->default(0);
            $table->boolean('virtual')->default(1);
            $table->tinyInteger('stock_management')->default(0);
            $table->unsignedBigInteger('vendor_id');

            $table->text('update_comment')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            // $table->unsignedBigInteger('productable_id');
            // $table->string('productable_type');
            // $table->foreign('user_id')->references('id')->on('users') ->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('sellers') ->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
}
