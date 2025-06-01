<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('conditions')->nullable();
            $table->text('sms')->nullable();
            $table->text('actions')->nullable();
            $table->string('model')->nullable();
            $table->string('trigger_when')->nullable();
            $table->string('execute_when')->nullable();
            $table->string('trigger_count')->nullable();
            $table->string('action_time')->nullable();
            $table->integer('mailable_id')->nullable();
            $table->text('selected_column')->nullable();


            $table->unsignedBigInteger('ou_id');
            $table->foreign('ou_id')->references('id')->on('ous')->onDelete('cascade');

            // $table->foreign('mailable_id')->references('id')->on('mailables')->onDelete('cascade');
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
        Schema::dropIfExists('automations');
    }
}
