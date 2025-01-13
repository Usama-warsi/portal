<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GoogleMeet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('google_meet')) {
            Schema::create('google_meet', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('description');
                $table->string('meeting_id');
                $table->timestamp('start_date');
                $table->string('duration')->nullable();
                $table->string('member_ids')->nullable();
                $table->string('start_url')->nullable();
                $table->string('join_url')->nullable();
                $table->string('status')->nullable();
                $table->string('created_by')->nullable();
                $table->string('workspace_id')->nullable();
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
        //
    }
}
