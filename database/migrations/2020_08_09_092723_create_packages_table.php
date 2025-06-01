<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_no');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sale_id');

            $table->decimal('quantity')->default(0);
            $table->decimal('shipping_charge')->default(0);

            $table->date('shipment_date')->nullable();
            $table->string('status')->nullable();
            
            $table->boolean('shiped')->default(false);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
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
        Schema::dropIfExists('packages');
    }
}
