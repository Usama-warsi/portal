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
        if(!Schema::hasTable('lms_pixel_fields'))
        {
            Schema::create('lms_pixel_fields', function (Blueprint $table) {
                $table->id();
                $table->string('platform')->nullable();
                $table->string('pixel_id')->nullable();
                $table->integer('store_id')->nullable();
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
        Schema::dropIfExists('lms_pixel_fields');
    }
};
