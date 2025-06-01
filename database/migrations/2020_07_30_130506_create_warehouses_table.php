<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('ou_id')->unsigned();
            $table->string('name')->nullable();
            $table->longText('phone')->nullable();
            $table->longText('location')->nullable();
            $table->string('length')->default(0);
            $table->string('width')->default(0);
            $table->string('height')->default(0);
            $table->string('non_storage')->default(0);
            $table->string('capacity')->default(0);

            $table->string('code')->nullable(0);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ou_id')->references('id')->on('ous')->onDelete('cascade');
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
        Schema::dropIfExists('warehouses');
    }
}
