<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_sales', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_status');
            $table->string('receive_status');
            $table->integer('sent')->default(0);
            $table->integer('received')->default(0);
            $table->string('comment')->nullable();
            $table->text('products')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('sale_id');

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
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
        Schema::dropIfExists('branch_sales');
    }
}
