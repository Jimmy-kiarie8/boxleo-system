<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asns', function (Blueprint $table) {
            $table->id();
            $table->string('asn_no');
            $table->unsignedBigInteger('vendor_id');
            $table->string('invoice_no');
            $table->string('po_number')->nullable();
            $table->string('product_id');
            $table->integer('quantity');
            $table->integer('weight')->nullable();
            $table->string('vehicle_no')->nullable();

            $table->foreign('vendor_id')->references('id')->on('sellers')->onDelete('cascade');
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
        Schema::dropIfExists('asns');
    }
}
