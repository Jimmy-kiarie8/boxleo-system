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
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->date('dispatch_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('status')->default('Pending');
            $table->text('warehouse_notes')->nullable();
            $table->boolean('quality_checked')->default(false);
            $table->string('waybill_no')->nullable();
            $table->text('quality_notes')->nullable();
            $table->integer('approval_level')->default(0);
            $table->json('products')->nullable();
            $table->text('approval_notes')->nullable();
            $table->text('approval_notes2')->nullable();
            $table->text('approval_notes3')->nullable();
            $table->text('approval_notes4')->nullable();
            $table->text('approval_notes5')->nullable();
            $table->text('approval_notes6')->nullable();
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
        Schema::dropIfExists('dispatches');
    }
};
