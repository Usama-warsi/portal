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
        if (!Schema::hasTable('sales_agents'))
        {
            Schema::create('sales_agents', function (Blueprint $table) {
                $table->id();
                $table->integer('agent_id');
                $table->integer('user_id');
                $table->integer('customer_id')->nullable();
                $table->integer('is_agent_active')->default(1);
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->rememberToken();
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
        Schema::dropIfExists('sales_agents');
    }
};
