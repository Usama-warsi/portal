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
        if (!Schema::hasTable('video_hub_modules')) {
            Schema::create('video_hub_modules', function (Blueprint $table) {
                $table->id();
                $table->string('module');
                $table->string('sub_module')->nullable();
                $table->text('field_json')->nullable();
                $table->string('type')->default('company');
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
        Schema::dropIfExists('video_hub_modules');
    }
};
