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
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->id();
            $table->string('api_key')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('client_id')->nullable();
            $table->string('username')->nullable();
            $table->string('number')->nullable();
            $table->string('token')->nullable();
            $table->string('partner_id')->nullable();
            $table->string('provider')->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('sms_providers');
    }
};
