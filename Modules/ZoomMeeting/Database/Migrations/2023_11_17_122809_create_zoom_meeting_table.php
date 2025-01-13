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
        if (!Schema::hasTable('zoom_meeting')) {
            Schema::create('zoom_meeting', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('meeting_id');
                $table->timestamp('start_date');
                $table->string('duration')->nullable();
                $table->longText('start_url')->nullable();
                $table->string('join_url')->nullable();
                $table->string('password')->nullable();
                $table->string('status')->nullable();
                $table->string('created_by')->nullable();
                $table->string('workspace_id')->nullable();
                $table->timestamps();
            });
            if (!Schema::hasTable('general_meeting')) {
                Schema::create('general_meeting', function (Blueprint $table) {
                    $table->id();
                    $table->integer('m_id');
                    $table->integer('user_id')->default(0);
                    $table->timestamps();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoom_meeting');
    }
};
