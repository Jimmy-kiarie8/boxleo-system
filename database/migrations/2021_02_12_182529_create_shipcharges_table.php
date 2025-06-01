<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipcharges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('sale_id');

            $table->boolean('remmited')->default(0);
            $table->dateTime('remmited_on')->nullable();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
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
        Schema::dropIfExists('shipcharges');
    }
}
