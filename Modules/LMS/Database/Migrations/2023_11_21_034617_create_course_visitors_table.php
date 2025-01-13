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
        if(!Schema::hasTable('course_visitors'))
        {
            Schema::create('course_visitors', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('method')->nullable();
                $table->mediumText('referer')->nullable();
                $table->text('languages')->nullable();
                $table->text('useragent')->nullable();
                $table->text('device')->nullable();
                $table->text('platform')->nullable();
                $table->text('browser')->nullable();
                $table->ipAddress('ip')->nullable();
                $table->text('slug')->nullable();
                $table->text('pageview')->nullable();
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
        Schema::dropIfExists('course_visitors');
    }
};
