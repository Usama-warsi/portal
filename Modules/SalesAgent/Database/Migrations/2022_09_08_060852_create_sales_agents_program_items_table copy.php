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
        if (!Schema::hasTable('sales_agents_program_items'))
        {
            Schema::create('sales_agents_program_items', function (Blueprint $table)
            {
                $table->id();
                $table->integer('program_id');
                $table->string('product_type');
                $table->string('items');
                $table->float('from_amount');
                $table->float('to_amount');
                $table->float('discount')->default('0.00');
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
        Schema::dropIfExists('sales_agents_purchase_items');
    }
};
