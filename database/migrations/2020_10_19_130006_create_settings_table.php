<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // $table->string('site')->nullable();
            $table->string('file_path')->nullable();
            $table->string('africas_talkig_username')->nullable();
            $table->string('africas_talkig_api_key')->nullable();
            $table->string('twilio_sid')->nullable();
            $table->string('twilio_auth_token')->nullable();
            $table->string('twilio_number')->nullable();
            $table->string('google_client_id')->nullable();
            $table->string('google_client_secret')->nullable();
            $table->string('google_refresh_token')->nullable();
            $table->string('google_folder_id')->nullable();
            // $table->string('woocommerce')->nullable();

            $table->string('woocommerce_url')->nullable();
            $table->string('woocommerce_consumer_secret')->nullable();
            $table->string('woocommerce_consumer_key')->nullable();
            $table->string('shopify_key')->nullable();
            $table->string('shopify_secret')->nullable();
            $table->string('shopify_redirect')->nullable();
            $table->string('shopify_access')->nullable();
            $table->string('shopify_url')->nullable();

            // celcomafrica
            $table->string('celcomafrica_partner_id')->nullable();
            $table->string('celcomafrica_api_key')->nullable();
            $table->string('celcomafrica_short_code')->nullable();

            $table->string('sms_default')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            // $table->foreign('vendor_id')->references('id')->on('sellers')->onDelete('cascade');

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
        Schema::dropIfExists('settings');
    }
}
