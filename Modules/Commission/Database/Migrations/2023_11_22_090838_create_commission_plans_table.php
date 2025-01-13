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
        if (!Schema::hasTable('commission_plans')) {

            Schema::create('commission_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->string('user_id')->nullable() ;
                $table->string('commission_type')->nullable();
                $table->longText('commission_str')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('commission_module')->nullable();
                $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('commission_plans');
    }
};
