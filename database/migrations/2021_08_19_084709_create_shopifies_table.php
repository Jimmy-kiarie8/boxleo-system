<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopifies', function (Blueprint $table) {
            $table->id();
            $table->string('shopify_key')->nullable();
            $table->string('shopify_secret');
            $table->string('shopify_url');
            $table->string('shopify_name');

            $table->boolean('active')->default(true);
            $table->boolean('auto_sync')->default(true);
            $table->boolean('new_api')->default(true);
            $table->boolean('order_webhook')->default(true);
            $table->string('webhook_id')->default(true);
            $table->boolean('product_webhook')->default(true);
            $table->integer('sync_interval')->default(30);
            $table->dateTime('last_order_synced')->nullable();
            $table->dateTime('last_product_synced')->nullable();
            $table->string('order_prefix')->nullable();
            $table->string('sync_option')->default('Webhook');

            $table->boolean('webhook_active')->default(false);

            
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('ou_id');
            $table->foreign('vendor_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('ou_id')->references('id')->on('ous')->onDelete('cascade');
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
        Schema::dropIfExists('shopifies');
    }
}
