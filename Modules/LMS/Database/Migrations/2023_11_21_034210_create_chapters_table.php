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
        if(!Schema::hasTable('chapters'))
        {
            Schema::create('chapters', function (Blueprint $table) {
                $table->id();
                $table->integer('header_id');
                $table->integer('course_id');
                $table->string('name');
                $table->integer('order_by')->nullable();
                $table->string('type');
                $table->string('duration')->nullable();
                $table->string('video_url')->nullable();
                $table->string('video_file')->nullable();
                $table->text('iframe')->nullable();
                $table->text('text_content')->nullable();
                $table->text('chapter_description')->nullable();
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
        Schema::dropIfExists('chapters');
    }
};
