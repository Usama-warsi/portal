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
        if (!Schema::hasTable('rotas')) {
            Schema::create('rotas', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->default(0);
                $table->integer('issued_by')->default(0);
                $table->date('rotas_date')->nullable();
                $table->string('start_time', 50)->nullable();
                $table->string('end_time', 50)->nullable();
                $table->string('break_time', 50)->default(0);
                $table->string('time_diff_in_minut')->default(0);
                $table->text('note')->nullable();
                $table->integer('designation_id')->default(0);
                $table->integer('publish')->default(1)->comment('0=>unpublish/1=>publish');
                $table->string('shift_status')->default('enable')->comment('enable/disable/request');
                $table->string('shift_cancel_employee_msg')->nullable();
                $table->string('shift_cancel_owner_msg')->nullable();
                $table->integer('weekly_hour')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('create_by')->default(0);
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
        Schema::dropIfExists('rotas');
    }
};
