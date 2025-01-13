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
        if (!Schema::hasTable('leave_requests')) {
            Schema::create('leave_requests', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->default(0);
                $table->integer('issue_by')->default(0)->comment('employee/ owner');
                $table->string('leave_type', 100)->nullable()->comment('1=>holiday(blue)/ 2=>other leave(green)/ 3=> other static leave (sickness,Public Holiday) (pink)');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->text('message')->nullable();
                $table->integer('leave_approval')->default('0')->comment('0=>new request/ 1=>confirm/ 2=>reject/ 3=>cancel');
                $table->integer('approved_by')->default('0');
                $table->date('approved_date')->nullable();
                $table->string('paid_status', 10)->nullable()->comment('paid/unpaid');
                $table->text('response_message')->nullable();
                $table->text('leave_time')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('leave_requests');
    }
};
