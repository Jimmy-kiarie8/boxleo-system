<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWoocommercesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woocommerces', function (Blueprint $table) {
            $table->id();
            $table->string('woocommerce_name')->nullable();
            $table->string('woocommerce_url')->nullable();
            $table->string('woocommerce_consumer_secret')->nullable();
            $table->string('woocommerce_consumer_key')->nullable();

            $table->boolean('active')->default(true);
            $table->boolean('auto_sync')->default(true);
            $table->boolean('order_webhook')->default(true);
            $table->boolean('product_webhook')->default(true);
            $table->integer('sync_interval')->default(30);
            $table->dateTime('last_order_synced')->nullable();
            $table->dateTime('last_product_synced')->nullable();
            $table->string('order_prefix')->nullable();
            $table->string('sync_option')->default('Webhook');
            
            $table->unsignedBigInteger('ou_id');
            $table->foreign('ou_id')->references('id')->on('ous')->onDelete('cascade');

            $table->unsignedBigInteger('vendor_id');
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
        Schema::dropIfExists('woocommerces');
    }
}
