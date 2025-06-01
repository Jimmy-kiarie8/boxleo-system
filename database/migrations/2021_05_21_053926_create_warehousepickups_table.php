<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousepickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehousepickups', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('arrival_date');
            $table->string('lot_no');
            // $table->date('price');
            // $table->date('total_fee');
            $table->integer('vendor_id');
            $table->text('data');
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
        Schema::dropIfExists('warehousepickups');
    }
}
