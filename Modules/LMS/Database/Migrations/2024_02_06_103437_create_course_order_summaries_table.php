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
        if(!Schema::hasTable('course_order_summaries'))
        {
            Schema::create('course_order_summaries', function (Blueprint $table) {
                $table->id();
                $table->string('order_id', 100);
                $table->integer('student_id');
                $table->date('issue_date');
                $table->text('course_number');
                $table->text('status');
                $table->float('price')->default(0);
                $table->text('course');
                $table->integer('workspace')->default(0);
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
        Schema::dropIfExists('course_order_summaries');
    }
};
