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
        Schema::create('shipment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('payment_code')->nullable();
            $table->decimal('amount')->nullable();
            $table->integer('stage')->default(1);
            $table->integer('approve_status')->default(false);
            $table->date('arrival_date');
            $table->enum('status', ['Pending', 'Received', 'Rejected', 'Partially Received'])->default('Pending');
            $table->foreign('seller_id')->references('id')->on('sellers');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
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
        Schema::dropIfExists('shipment_requests');
    }
};
