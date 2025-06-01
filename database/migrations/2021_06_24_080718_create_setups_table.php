<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setups', function (Blueprint $table) {
            $table->id();
            $table->boolean('company')->default(0);
            $table->boolean('company_logo')->default(0);
            $table->boolean('warehouse')->default(0);
            $table->boolean('products')->default(0);
            $table->boolean('zones')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('services')->default(0);
            $table->boolean('vendors')->default(0);
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
        Schema::dropIfExists('setups');
    }
}
