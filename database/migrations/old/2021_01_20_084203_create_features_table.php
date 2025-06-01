<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default(0);
            $table->integer('portals')->default(0);
            $table->integer('users')->default(0);
            $table->integer('tracking')->default(0);
            $table->integer('warehouses')->default(0);
            $table->integer('ous')->default(0);
            $table->integer('shopify_integrations')->default(0);
            $table->integer('wordpress_integrations')->default(0);
            $table->integer('api_integrations')->default(0);
            $table->integer('automations')->default(0);
            $table->integer('sms')->default(0);
            $table->integer('emails')->default(0);
            $table->integer('lables')->default(0);
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
        Schema::dropIfExists('features');
    }
}
