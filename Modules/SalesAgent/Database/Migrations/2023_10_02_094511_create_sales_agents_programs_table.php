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
        if (!Schema::hasTable('sales_agents_programs'))
        {
            Schema::create('sales_agents_programs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->date('from_date')->nullable();
                $table->date('to_date')->nullable();
                $table->longText('description')->nullable();
                $table->string('discount_type')->nullable();
                $table->string('sales_agents_applicable')->nullable();
                $table->string('sales_agents_view')->nullable();
                $table->string('requests_to_join')->nullable();
                $table->integer('workspace');
                $table->integer('created_by');
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
        Schema::dropIfExists('sales_agents_programs');
    }
};
