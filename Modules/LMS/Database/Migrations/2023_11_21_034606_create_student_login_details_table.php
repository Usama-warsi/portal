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
        if(!Schema::hasTable('student_login_details'))
        {
            Schema::create('student_login_details', function (Blueprint $table) {
                $table->id();
                $table->integer('student_id')->nullable();
                $table->string('ip')->nullable();
                $table->dateTime('date')->nullable();
                $table->longText('details')->nullable();
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
        Schema::dropIfExists('student_login_details');
    }
};
