<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bins', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('row_id');
            $table->unsignedBigInteger('bay_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('area_id');
            $table->integer('quantity')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade'); 
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade'); 
            $table->foreign('bay_id')->references('id')->on('bays')->onDelete('cascade'); 
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade'); 
            $table->foreign('row_id')->references('id')->on('rows')->onDelete('cascade');             
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
        Schema::dropIfExists('bins');
    }
}
