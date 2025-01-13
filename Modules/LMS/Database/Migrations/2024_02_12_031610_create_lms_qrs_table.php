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
        if(!Schema::hasTable('lms_qrs'))
        {
            Schema::create('lms_qrs', function (Blueprint $table) {
                $table->id();
                $table->string('foreground_color')->nullable();
                $table->string('background_color')->nullable();
                $table->string('radius')->nullable();
                $table->string('qr_type')->nullable();
                $table->string('qr_text')->nullable();
                $table->string('qr_text_color')->nullable();
                $table->string('image')->nullable();
                $table->string('size')->nullable();
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
        Schema::dropIfExists('lms_qrs');
    }
};
