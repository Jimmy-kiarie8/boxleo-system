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
        Schema::create('shipping_reqs', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount')->nullable();
            $table->string('payment_code')->nullable();
            $table->date('arrival_date')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('waybill_no')->nullable();
            $table->decimal('weight')->nullable();
            $table->integer('stage')->default(1);
            $table->string('last_aproved_by')->nullable();
            $table->boolean('approve_status')->default(false);

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');

            $table->enum('status', ['Pending', 'Received', 'Rejected', 'Partially Received'])->default('Pending');

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
        Schema::dropIfExists('shipping_reqs');
    }
};
