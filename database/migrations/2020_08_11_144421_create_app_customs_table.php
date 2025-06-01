<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_customs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('columns')->nullable();
            $table->text('labels')->nullable();
            $table->string('model')->nullable();
            $table->text('order')->nullable();
            $table->text('conditions')->nullable();

            $table->boolean('app_view')->default(false);

            $table->unsignedBigInteger('user_id');

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('app_customs');
    }
}
