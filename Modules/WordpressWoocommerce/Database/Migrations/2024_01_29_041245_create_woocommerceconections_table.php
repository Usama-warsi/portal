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
        if (!Schema::hasTable('woocommerceconections'))
        {
            Schema::create('woocommerceconections', function (Blueprint $table) {
                $table->id();
                $table->string('type')->nullable();
                $table->bigInteger('woocomerce_id');
                $table->integer('original_id');
                $table->integer('workspace_id')->nullable();
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
        Schema::dropIfExists('woocommerceconections');
    }
};
