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
        if(!Schema::hasTable('rattings'))
        {
            Schema::create('rattings', function (Blueprint $table) {
                $table->id();
                $table->string('slug');
                $table->string('course_id')->nullable();
                $table->string('student_id')->nullable();
                $table->string('tutor_id')->nullable();
                $table->string('name')->nullable();
                $table->string('title')->nullable();
                $table->string('rating_view')->default('on');
                $table->string('ratting')->nullable();
                $table->text('description')->nullable();
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
        Schema::dropIfExists('rattings');
    }
};
