<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_sales', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_status');
            $table->string('receive_status');
            $table->integer('sent')->default(0);
            $table->integer('received')->default(0);
            $table->string('comment')->nullable();
            $table->text('products')->nullable();
            $table->unsignedBigInteger('rider_id');
            $table->unsignedBigInteger('sale_id');
            $table->date('dispatch_date')->nullable();

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');
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
        Schema::dropIfExists('rider_sales');
    }
}
