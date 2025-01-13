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
        if(!Schema::hasTable('asset_defectives'))
        {
            Schema::create('asset_defectives', function (Blueprint $table) {
                $table->id();
                $table->text('type')->nullable();
                $table->text('code')->nullable();
                $table->integer('asset_id')->nullable();
                $table->float('branch')->nullable();
                $table->integer('employee_id')->nullable();
                $table->date('date')->nullable();
                $table->text('reason')->nullable();
                $table->text('quantity')->nullable();
                $table->text('status')->nullable();
                $table->text('image')->nullable();
                $table->text('urgency_level')->nullable();
                $table->integer('created_by')->default(0);
                $table->integer('workspace_id')->default(0);
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
        Schema::dropIfExists('asset_defectives');
    }
};
