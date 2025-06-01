<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('discount');
            // $table->json('attribute_data');
            $table->integer('status');
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('stop_rules')->default(false);
            $table->integer('uses')->default(0);
            $table->integer('lower_limit')->unsigned()->default(1);
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
        Schema::dropIfExists('discounts');
    }
}
