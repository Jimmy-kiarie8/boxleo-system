<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_stats', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');

            $table->integer('delivered')->default(0);
            $table->integer('dispatched')->default(0);
            $table->integer('scheduled')->default(0);
            $table->integer('uploaded')->default(0);
            $table->integer('returned')->default(0);
            $table->integer('pedding')->default(0);
            $table->integer('closing_count')->default(0);
            $table->integer('starting_counter')->default(0);
            $table->date('date')->default(0);
            $table->unsignedBigInteger('ou_id');

            $table->foreign('ou_id')->references('id')->on('ous')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('daily_stats');
    }
}
