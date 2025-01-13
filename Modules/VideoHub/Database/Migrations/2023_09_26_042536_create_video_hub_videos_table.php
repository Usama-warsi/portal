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
        if (!Schema::hasTable('video_hub_videos')) {
            Schema::create('video_hub_videos', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('module');
                $table->string('sub_module_id')->nullable();
                $table->string('item_id')->nullable();
                $table->text('type');
                $table->text('thumbnail')->nullable();
                $table->text('video')->nullable();
                $table->text('description')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by');
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
        Schema::dropIfExists('video_hub_videos');
    }
};
