<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheets', function (Blueprint $table) {
            $table->id();
            $table->string('sheet_name')->nullable();
            $table->string('post_spreadsheet_id')->nullable();

            $table->boolean('active')->default(true);
            $table->boolean('auto_sync')->default(true);
            $table->boolean('sync_all')->default(false);
            $table->integer('sync_interval')->default(30);
            $table->dateTime('last_order_synced')->nullable();
            $table->dateTime('last_order_upload')->nullable();
            $table->dateTime('last_product_synced')->nullable();
            $table->string('order_prefix')->nullable();
            $table->string('lastUpdatedOrderNumber')->nullable();

            $table->boolean('is_current')->default(false);

            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('ou_id');
            $table->foreign('vendor_id')->references('id')->on('sellers')->onDelete('cascade');
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
        Schema::dropIfExists('sheets');
    }
}
