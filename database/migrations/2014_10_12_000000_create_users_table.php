<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->date('last_login')->nullable();
            $table->date('first_login')->nullable();
            $table->boolean('drawer_open')->default(false);
            $table->text('agent_token')->nullable();
            $table->string('agent_sip')->nullable();

            $table->string('last_session')->nullable();
            $table->string('status')->nullable();

            $table->boolean('on_break')->default(true);
            $table->boolean('call_active')->default(false);
            $table->boolean('can_call')->default(false);
            $table->boolean('can_receive_calls')->default(false);
            $table->boolean('can_receive_orders')->default(false);

            $table->boolean('active')->default(true);

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('ou_id')->nullable();


            $table->foreignId('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();

            $table->text('agent_id')->nullable();


            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            // $table->foreign('ou_id')->references('id')->on('ous')->onDelete('cascade');
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
        Schema::dropIfExists('users');
    }
}
