<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ous', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('ou');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('company_id');
            $table->string('currency_code')->nullable();
            $table->string('ou_code')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('ou_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_code')->nullable();
            $table->text('waybill_terms')->nullable();
            
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
        Schema::dropIfExists('ous');
    }
}
