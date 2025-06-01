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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->string('callSessionState');
            $table->string('direction');
            $table->string('callerCountryCode');
            $table->decimal('durationInSeconds')->default(0);
            $table->decimal('amount')->default(0);
            $table->decimal('charged_amount')->default(0);
            $table->string('callerNumber');
            $table->string('destinationNumber');
            $table->string('callerCarrierName')->nullable();
            $table->string('status')->nullable();
            $table->string('sessionId');
            $table->boolean('isActive')->default(0);
            $table->boolean('downloaded')->default(0);
            $table->string('currencyCode')->nullable();
            $table->boolean('inhouse')->default(0);
            $table->string('dialDestinationNumber')->nullable();
            $table->decimal('dialDurationInSeconds')->nullable();
            $table->string('call_status')->nullable();
            $table->text('recordingUrl')->nullable();
            $table->text('recordUrl')->nullable();
            $table->string('lastBridgeHangupCause')->nullable();

            $table->integer('sale_id')->nullable();
            $table->dateTime('dialStartTime')->nullable();
            $table->dateTime('callStartTime')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('calls');
    }
};
