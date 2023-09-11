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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->text('order_number');
            $table->double('order_amount');
            $table->text('order_currency');
            $table->text('order_description');
            $table->text('type');
            $table->text('status');
            $table->text('reason')->nullable();
            $table->string('card')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('hash')->nullable();
            $table->string('card_expiration_date')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_country')->nullable();
            $table->string('customer_state')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_ip')->nullable();
            $table->text('transaction_id');
            $table->string('exchange_rate_base')->nullable();
            $table->string('exchange_rate')->nullable();
            $table->string('exchange_currency')->nullable();
            $table->string('exchange_amount')->nullable();
            $table->string('merchantId')->nullable();
            $table->string('rrn')->nullable();
            $table->string('approval_code')->nullable();
            $table->string('card_token')->nullable();
            $table->string('recurring_init_trans_id')->nullable();
            $table->string('recurring_token')->nullable();
            $table->string('schedule_id')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
