<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_docs', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('file_name');
            $table->unsignedBigInteger('shipping_req_id');
            $table->foreign('shipping_req_id')->references('id')->on('shipping_reqs')->onDelete('cascade');
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
        Schema::dropIfExists('shipping_docs');
    }
};
