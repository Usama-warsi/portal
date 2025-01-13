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

        if (!Schema::hasTable('video_hub_comments')) {
            Schema::create('video_hub_comments', function (Blueprint $table) {
                $table->id();
                $table->integer('video_id');
                $table->string('file')->nullable();
                $table->text('comment')->nullable();
                $table->integer('parent')->default('0');
                $table->integer('comment_by')->default('0');
                $table->integer('workspace')->nullable();
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
        Schema::dropIfExists('video_hub_comments');
    }
};
