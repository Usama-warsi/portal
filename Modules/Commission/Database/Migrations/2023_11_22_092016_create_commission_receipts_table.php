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
        if (!Schema::hasTable('commission_receipts')) {

            Schema::create('commission_receipts', function (Blueprint $table) {
                $table->id();
                $table->string('commission_date')->nullable();
                $table->string('commission_str')->nullable();
                $table->integer('commissionplan_id')->nullable();
                $table->string('agent')->nullable();
                $table->string('amount')->default('0.00');
                $table->integer('status')->default(0);
                $table->integer('workspace')->nullable();
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
        Schema::dropIfExists('commission_receipts');
    }
};
