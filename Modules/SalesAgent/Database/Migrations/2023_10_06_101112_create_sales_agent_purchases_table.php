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
        if (!Schema::hasTable('sales_agent_purchases'))
        {
            Schema::create('sales_agent_purchases', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->integer('invoice_id')->nullable();
                $table->integer('purchaseOrder_id')->nullable();
                $table->string('order_name')->nullable();
                $table->string('order_number')->nullable();
                $table->string('order_date')->nullable();
                $table->string('delivery_date')->nullable();
                $table->string('delivery_status')->nullable();
                $table->string('approval_status')->nullable();
                $table->string('order_status')->nullable();

                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default('0');
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
        Schema::dropIfExists('sales_agent_purchases');
    }
};
