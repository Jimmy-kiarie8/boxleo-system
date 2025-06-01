<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_views', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('columns')->nullable();
            $table->text('labels')->nullable();
            $table->string('model')->nullable();
            $table->text('order')->nullable();
            $table->text('conditions')->nullable();

            $table->boolean('app_view')->default(false);

            $table->unsignedBigInteger('user_id');

            $table->integer('app_custom_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('custom_views');
    }
}
