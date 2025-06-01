<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_stats', function (Blueprint $table) {
            $table->id();
            $table->integer('delivered')->default(0);
            $table->integer('dispatched')->default(0);
            $table->integer('scheduled')->default(0);
            $table->integer('uploaded')->default(0);
            $table->integer('returned')->default(0);
            $table->integer('pedding')->default(0);
            $table->unsignedBigInteger('ou_id');

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
        Schema::dropIfExists('order_stats');
    }
}
