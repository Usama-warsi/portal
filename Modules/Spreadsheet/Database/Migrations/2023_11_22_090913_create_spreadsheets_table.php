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
        if(!Schema::hasTable('spreadsheets'))
        {
            Schema::create('spreadsheets', function (Blueprint $table) {
                $table->id();
                $table->string('folder_name');
                $table->string('path')->nullable();
                $table->integer('parent_id')->default(0);
                $table->integer('user_id')->default(0);
                $table->string('user_assign')->nullable();
                $table->string('user_and_per')->nullable();
                $table->string('related')->nullable();
                $table->string('related_assign')->nullable();
                $table->string('type')->nullable();
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
        Schema::dropIfExists('spreadsheets');
    }
};
