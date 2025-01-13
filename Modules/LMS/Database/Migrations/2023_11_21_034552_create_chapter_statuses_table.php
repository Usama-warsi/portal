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
        if(!Schema::hasTable('chapter_statuses'))
        {
            Schema::create('chapter_statuses', function (Blueprint $table) {
                $table->id();
                $table->integer('course_id');
                $table->integer('chapter_id');
                $table->integer('student_id');
                $table->string('status');
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
        Schema::dropIfExists('chapter_statuses');
    }
};
