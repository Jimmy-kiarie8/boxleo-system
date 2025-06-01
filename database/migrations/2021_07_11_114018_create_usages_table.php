<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usages', function (Blueprint $table) {
            $table->id();
            $table->integer('sms')->default(0);
            $table->integer('emails')->default(0);
            $table->integer('trackings')->default(0);
            $table->integer('labels')->default(0);
            $table->integer('integrations')->default(0);
            $table->integer('sms_bundles')->default(0);
            $table->integer('sms_remaining')->default(0);
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
        Schema::dropIfExists('usages');
    }
}
