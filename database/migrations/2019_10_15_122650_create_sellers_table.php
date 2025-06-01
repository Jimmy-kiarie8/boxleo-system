<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('active')->default(true);
            $table->string('shopify_email')->nullable();

            $table->string('order_no_start')->nullable();
            $table->string('order_no_end')->nullable();

            $table->boolean('autogenerate')->default(0);
            $table->boolean('portal_active')->default(0);
            $table->text('terms')->nullable();

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('ou_id');

            $table->boolean('sheet_update')->default(true);
            $table->boolean('send_sms')->default(true);
            $table->boolean('telegram_notifications')->default(true);


            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('ou_id')->references('id')->on('ous')->onDelete('cascade');
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('sellers');
    }
}
