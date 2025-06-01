<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('drawer_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('agent_id')->nullable();
            $table->decimal('total_price')->nullable();
            $table->integer('scale')->default(1);
            $table->decimal('invoice_value')->nullable();
            $table->decimal('amount_paid')->nullable();
            $table->decimal('sub_total')->nullable();
            $table->string('order_no')->nullable();
            $table->string('sku_no')->nullable();

            $table->string('tracking_no')->nullable();
            $table->string('waybill_no')->nullable();

            $table->text('customer_notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->text('cancel_notes')->nullable();
            $table->decimal('discount')->default(0);
            $table->decimal('shipping_charges')->default(0);
            $table->decimal('charges')->default(0);
            $table->dateTime('delivery_date')->nullable();
            $table->string('status')->default('Inprogress');
            $table->string('delivery_status')->default('Inprogress');

            $table->integer('warehouse_id')->nullable();
            $table->integer('seller_id')->nullable();

            $table->text('paypal')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            // $table->unsignedBigInteger('sku_id');
            $table->integer('courier_id')->nullable();

            // $table->foreign('sku_id')->references('id')->on('skus') ->onDelete('cascade');

            $table->string('mpesa_code')->nullable();

            $table->string('route')->nullable();

            $table->string('terms')->nullable();
            $table->string('template_name')->nullable();
            $table->string('platform')->default('upload');

            $table->boolean('is_return_waiting_for_approval')->default(false);
            $table->boolean('is_salesreturn_allowed')->default(false);
            $table->boolean('is_test_order')->default(false);
            $table->boolean('is_emailed')->default(false);
            $table->boolean('is_dropshipped')->default(false);
            $table->boolean('is_cancel_item_waiting_for_approval')->default(false);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('upsell')->default(false);

            $table->boolean('confirmed')->default(false);
            $table->boolean('delivered')->default(false);
            $table->boolean('returned')->default(false);
            $table->boolean('cancelled')->default(false);
            $table->boolean('invoiced')->default(false);
            $table->boolean('packed')->default(false);
            $table->boolean('printed')->default(false);
            $table->boolean('sticker_printed')->default(false);
            $table->boolean('prepaid')->default(false);
            
            $table->boolean('paid')->default(false);
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->decimal('distance')->nullable();
            $table->decimal('boxes')->nullable();
            $table->string('loading_no')->nullable();
            $table->boolean('geocoded')->default(false);
            $table->decimal('weight')->default(0)->nullable();

            $table->integer('return_count')->default(0);

            $table->dateTime('dispatched_on')->nullable();
            $table->dateTime('delivered_on')->nullable();
            $table->dateTime('schedule_date')->nullable();
            $table->dateTime('returned_on')->nullable();
            $table->dateTime('cancelled_on')->nullable();
            $table->dateTime('return_date')->nullable();
            $table->dateTime('printed_at')->nullable();
            $table->string('print_no')->nullable();
            $table->dateTime('sticker_at')->nullable();
            $table->date('recall_date')->nullable();

            $table->text('history_comment')->nullable();

            $table->unsignedBigInteger('ou_id');

            $table->dateTime('archived_at')->nullable();


            $table->string('pickup_address')->nullable();
            $table->string('pickup_phone')->nullable();
            $table->string('pickup_shop')->nullable();
            $table->string('pickup_city')->nullable();

            $table->foreign('ou_id')->references('id')->on('ous')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users') ->onDelete('cascade');
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
        Schema::dropIfExists('sales');
    }
}
