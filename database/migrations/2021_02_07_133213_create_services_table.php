<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('service_from');
            $table->string('service_to');
            $table->string('charges_type');
            $table->decimal('charges');
            $table->string('charge_frequency');
            // $table->text('zone_exept')->nullable();

            $table->string('city_from')->nullable();
            $table->string('city_to')->nullable();
            $table->longText('zone_to')->nullable();
            $table->string('zone_from')->nullable();
            $table->string('charge_on')->nullable();
            $table->text('conditions')->nullable();
            $table->integer('ou_id')->default(1);
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
        Schema::dropIfExists('services');
    }
}
