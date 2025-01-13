<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('retainer_payments')) {

            Schema::create('retainer_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('retainer_id');
                $table->date('date');
                $table->float('amount')->default('0.00');
                $table->integer('account_id');
                $table->integer('payment_method');
                $table->string('receipt')->nullable();
                $table->string('payment_type')->default('Manually');
                $table->string('txn_id')->nullable();
                $table->string('currency')->nullable();
                $table->string('order_id')->nullable();
                $table->string('reference')->nullable();
                $table->string('add_receipt')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retainer_retainer_payments');
    }
};
