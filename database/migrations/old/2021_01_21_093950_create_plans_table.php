<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id')->nullable();
            $table->string('plan')->nullable();
            $table->decimal('amount')->nullable();
            $table->text('description')->nullable();
            $table->text('features')->nullable();
            $table->integer('feature_id')->nullable();
            $table->string('platform')->nullable();
            $table->string('subscription_id')->nullable();
            $table->text('data')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('orders')->default(0);

            $table->integer('portals')->default(0);
            $table->integer('users')->default(0);
            $table->integer('tracking')->default(0);
            $table->integer('warehouses')->default(0);
            $table->integer('ou')->default(0);
            $table->integer('shopify_integrations')->default(0);
            $table->integer('wordpress_integrations')->default(0);
            $table->integer('api_integrations')->default(0);
            $table->integer('automations')->default(0);
            $table->integer('sms')->default(0);
            $table->integer('emails')->default(0);
            $table->integer('lables')->default(0);

            $table->boolean('warehouse_management')->default(0);
             $table->boolean('inventory_management')->default(0);
             $table->boolean('packing_list')->default(0);

             $table->boolean('has_trial')->default(0);
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
        Schema::dropIfExists('plans');
    }
}
